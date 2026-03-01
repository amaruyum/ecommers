import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { ItemService } from '../../../features/catalogo/services/item.service';
import { CategoriaService } from '../../../features/catalogo/services/categoria.service';
import { Item } from '../../../features/catalogo/models/item.model';
import { Categoria } from '../../../features/catalogo/models/categoria.model';

@Component({
  selector: 'app-admin-items',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './items.component.html',
  styleUrls: ['./items.component.scss']
})
export class AdminItemsComponent implements OnInit {
  private fb              = inject(FormBuilder);
  private itemService     = inject(ItemService);
  private categoriaService = inject(CategoriaService);

  items       = signal<Item[]>([]);
  categorias  = signal<Categoria[]>([]);
  loading     = signal(false);
  saving      = signal(false);
  errorMsg    = signal('');
  successMsg  = signal('');
  showModal   = signal(false);
  showConfirm = signal(false);
  editingId   = signal<number | null>(null);
  deletingId  = signal<number | null>(null);
  deletingNombre = signal('');

  filterSearch    = signal('');
  filterEstado    = signal('');
  filterTipo      = signal('');
  filterCategoria = signal('');

  tipoSeleccionado = signal<'producto' | 'servicio'>('producto');

  form = this.fb.group({
    // Base
    tipo:         ['producto', Validators.required],
    id_categoria: ['', Validators.required],
    nombre:       ['', [Validators.required, Validators.maxLength(255)]],
    descripcion:  [''],
    estado:       ['activo', Validators.required],
    precio:       ['', [Validators.required, Validators.min(0)]],
    // Producto
    marca:             [''],
    fecha_elaboracion: [''],
    fecha_caducidad:   [''],
    stock_disponible:  ['0'],
    // Servicio
    tipo_servicio:         [''],
    fecha_inicio:          [''],
    fecha_fin:             [''],
    itinerario:            [''],
    lugar:                 [''],
    cupos_totales:         [''],
    cupos_disponibles:     [''],
    politicas_cancelacion: [''],
  });

  ngOnInit() {
    this.loadItems();
    this.loadCategorias();
    this.form.get('tipo')?.valueChanges.subscribe(v => {
      this.tipoSeleccionado.set(v as 'producto' | 'servicio');
    });
  }

  loadItems() {
    this.loading.set(true);
    this.itemService.getAll({
      search:       this.filterSearch(),
      estado:       this.filterEstado(),
      tipo:         this.filterTipo(),
      id_categoria: this.filterCategoria() ? +this.filterCategoria() : undefined,
    }).subscribe({
      next: res => { this.items.set(res.data); this.loading.set(false); },
      error: ()  => { this.loading.set(false); this.errorMsg.set('Error al cargar items'); }
    });
  }

  loadCategorias() {
    this.categoriaService.getAll({ estado: 'activo' }).subscribe({
      next: res => this.categorias.set(res.data)
    });
  }

  openCreate() {
    this.editingId.set(null);
    this.form.reset({ tipo: 'producto', estado: 'activo', stock_disponible: '0' });
    this.tipoSeleccionado.set('producto');
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  openEdit(item: Item) {
    this.editingId.set(item.id_item);
    this.tipoSeleccionado.set(item.tipo);
    this.form.patchValue({
      tipo:         item.tipo,
      id_categoria: item.id_categoria.toString(),
      nombre:       item.nombre,
      descripcion:  item.descripcion ?? '',
      estado:       item.estado,
      precio:       item.precio.toString(),
      // Producto
      marca:             item.producto?.marca ?? '',
      fecha_elaboracion: item.producto?.fecha_elaboracion ?? '',
      fecha_caducidad:   item.producto?.fecha_caducidad ?? '',
      stock_disponible:  item.producto?.stock_disponible?.toString() ?? '0',
      // Servicio
      tipo_servicio:         item.servicio?.tipo_servicio ?? '',
      fecha_inicio:          item.servicio?.fecha_inicio?.substring(0, 16) ?? '',
      fecha_fin:             item.servicio?.fecha_fin?.substring(0, 16) ?? '',
      itinerario:            item.servicio?.itinerario ?? '',
      lugar:                 item.servicio?.lugar ?? '',
      cupos_totales:         item.servicio?.cupos_totales?.toString() ?? '',
      cupos_disponibles:     item.servicio?.cupos_disponibles?.toString() ?? '',
      politicas_cancelacion: item.servicio?.politicas_cancelacion ?? '',
    });
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  closeModal() { this.showModal.set(false); this.errorMsg.set(''); }

  onSubmit() {
    if (this.form.invalid) { this.form.markAllAsTouched(); return; }
    this.saving.set(true);
    this.errorMsg.set('');

    const v    = this.form.value;
    const id   = this.editingId();

    const payload: any = {
      tipo:         v.tipo,
      id_categoria: +v.id_categoria!,
      nombre:       v.nombre,
      descripcion:  v.descripcion,
      estado:       v.estado,
      precio:       +v.precio!,
    };

    if (v.tipo === 'producto') {
      payload.marca             = v.marca;
      payload.fecha_elaboracion = v.fecha_elaboracion || null;
      payload.fecha_caducidad   = v.fecha_caducidad   || null;
      payload.stock_disponible  = +v.stock_disponible!;
    } else {
      payload.tipo_servicio         = v.tipo_servicio;
      payload.fecha_inicio          = v.fecha_inicio   || null;
      payload.fecha_fin             = v.fecha_fin       || null;
      payload.itinerario            = v.itinerario;
      payload.lugar                 = v.lugar;
      payload.cupos_totales         = +v.cupos_totales!;
      payload.cupos_disponibles     = +v.cupos_disponibles!;
      payload.politicas_cancelacion = v.politicas_cancelacion;
    }

    const req$ = id
      ? this.itemService.update(id, payload)
      : this.itemService.create(payload);

    req$.subscribe({
      next: res => {
        this.saving.set(false);
        this.showModal.set(false);
        this.successMsg.set(res.message);
        this.loadItems();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: err => {
        this.saving.set(false);
        if (err.status === 422 && err.error?.errors) {
          this.errorMsg.set(Object.values(err.error.errors).flat().join(', ') as string);
        } else {
          this.errorMsg.set(err.error?.message ?? 'Error al guardar');
        }
      }
    });
  }

  confirmDelete(item: Item) {
    this.deletingId.set(item.id_item);
    this.deletingNombre.set(item.nombre);
    this.showConfirm.set(true);
  }

  cancelDelete() { this.showConfirm.set(false); }

  doDelete() {
    const id = this.deletingId();
    if (!id) return;
    this.itemService.delete(id).subscribe({
      next: res => {
        this.showConfirm.set(false);
        this.successMsg.set(res.message);
        this.loadItems();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: err => {
        this.showConfirm.set(false);
        this.errorMsg.set(err.error?.message ?? 'Error al eliminar');
        setTimeout(() => this.errorMsg.set(''), 4000);
      }
    });
  }

  onSearch(e: Event)          { this.filterSearch.set((e.target as HTMLInputElement).value);  this.loadItems(); }
  onFilterEstado(e: Event)    { this.filterEstado.set((e.target as HTMLSelectElement).value);  this.loadItems(); }
  onFilterTipo(e: Event)      { this.filterTipo.set((e.target as HTMLSelectElement).value);    this.loadItems(); }
  onFilterCat(e: Event)       { this.filterCategoria.set((e.target as HTMLSelectElement).value); this.loadItems(); }

  get nombre()       { return this.form.get('nombre'); }
  get id_categoria() { return this.form.get('id_categoria'); }
  get precio()       { return this.form.get('precio'); }
  get esProducto()   { return this.tipoSeleccionado() === 'producto'; }
  get esServicio()   { return this.tipoSeleccionado() === 'servicio'; }
}