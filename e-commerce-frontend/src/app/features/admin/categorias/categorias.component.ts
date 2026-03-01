import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { CategoriaService } from '../../../features/catalogo/services/categoria.service';
import { Categoria } from '../../../features/catalogo/models/categoria.model';

@Component({
  selector: 'app-admin-categorias',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './categorias.component.html',
  styleUrls: ['./categorias.component.scss']
})
export class AdminCategoriasComponent implements OnInit {
  private fb      = inject(FormBuilder);
  private service = inject(CategoriaService);

  categorias   = signal<Categoria[]>([]);
  loading      = signal(false);
  saving       = signal(false);
  errorMsg     = signal('');
  successMsg   = signal('');
  showModal    = signal(false);
  showConfirm  = signal(false);
  editingId    = signal<number | null>(null);
  deletingId   = signal<number | null>(null);
  deletingNombre = signal('');

  searchTerm = signal('');
  filterTipo = signal('');
  filterEstado = signal('');

  form = this.fb.group({
    nombre:      ['', [Validators.required, Validators.maxLength(150)]],
    descripcion: [''],
    tipo:        ['producto', Validators.required],
    estado:      ['activo', Validators.required],
  });

  ngOnInit() { this.loadCategorias(); }

  loadCategorias() {
    this.loading.set(true);
    this.service.getAll({
      tipo:   this.filterTipo(),
      estado: this.filterEstado(),
      search: this.searchTerm(),
    }).subscribe({
      next: res => { this.categorias.set(res.data); this.loading.set(false); },
      error: ()  => { this.loading.set(false); this.errorMsg.set('Error al cargar categorías'); }
    });
  }

  openCreate() {
    this.editingId.set(null);
    this.form.reset({ nombre: '', descripcion: '', tipo: 'producto', estado: 'activo' });
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  openEdit(cat: Categoria) {
    this.editingId.set(cat.id_categoria);
    this.form.patchValue({
      nombre:      cat.nombre,
      descripcion: cat.descripcion ?? '',
      tipo:        cat.tipo,
      estado:      cat.estado,
    });
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  closeModal() { this.showModal.set(false); this.errorMsg.set(''); }

  onSubmit() {
    if (this.form.invalid) { this.form.markAllAsTouched(); return; }
    this.saving.set(true);
    this.errorMsg.set('');

    const data = this.form.value as any;
    const id   = this.editingId();

    const req$ = id
      ? this.service.update(id, data)
      : this.service.create(data);

    req$.subscribe({
      next: (res) => {
        this.saving.set(false);
        this.showModal.set(false);
        this.successMsg.set(res.message);
        this.loadCategorias();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: (err) => {
        this.saving.set(false);
        if (err.status === 422 && err.error?.errors) {
          const msgs = Object.values(err.error.errors).flat().join(', ');
          this.errorMsg.set(msgs as string);
        } else {
          this.errorMsg.set(err.error?.message ?? 'Error al guardar');
        }
      }
    });
  }

  confirmDelete(cat: Categoria) {
    this.deletingId.set(cat.id_categoria);
    this.deletingNombre.set(cat.nombre);
    this.showConfirm.set(true);
  }

  cancelDelete() { this.showConfirm.set(false); this.deletingId.set(null); }

  doDelete() {
    const id = this.deletingId();
    if (!id) return;
    this.service.delete(id).subscribe({
      next: (res) => {
        this.showConfirm.set(false);
        this.successMsg.set(res.message);
        this.loadCategorias();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: (err) => {
        this.showConfirm.set(false);
        this.errorMsg.set(err.error?.message ?? 'Error al eliminar');
        setTimeout(() => this.errorMsg.set(''), 4000);
      }
    });
  }

  onSearch(e: Event)      { this.searchTerm.set((e.target as HTMLInputElement).value); this.loadCategorias(); }
  onFilterTipo(e: Event)  { this.filterTipo.set((e.target as HTMLSelectElement).value); this.loadCategorias(); }
  onFilterEstado(e: Event){ this.filterEstado.set((e.target as HTMLSelectElement).value); this.loadCategorias(); }

  get nombre()      { return this.form.get('nombre'); }
  get tipo()        { return this.form.get('tipo'); }
  get estado()      { return this.form.get('estado'); }
}