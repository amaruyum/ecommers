import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { ContenidoService } from '../../../features/contenido/services/contenido.service';
import { Contenido } from '../../../features/contenido/models/contenido.model';

@Component({
  selector: 'app-admin-contenido',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './contenido.component.html',
  styleUrls: ['./contenido.component.scss']
})
export class AdminContenidoComponent implements OnInit {
  private fb      = inject(FormBuilder);
  private service = inject(ContenidoService);

  contenidos   = signal<Contenido[]>([]);
  loading      = signal(false);
  saving       = signal(false);
  errorMsg     = signal('');
  successMsg   = signal('');
  showModal    = signal(false);
  showConfirm  = signal(false);
  editingId    = signal<number | null>(null);
  deletingId   = signal<number | null>(null);
  deletingTitulo = signal('');
  filterTipo   = signal('');

  tipos = [
    { value: 'articulo', label: 'Artículo' },
    { value: 'noticia',  label: 'Noticia' },
    { value: 'video',    label: 'Video' },
    { value: 'anuncio',  label: 'Anuncio' },
  ];

  form = this.fb.group({
    titulo:         ['', Validators.required],
    cuerpo:         ['', Validators.required],
    tipo_contenido: ['articulo', Validators.required],
  });

  ngOnInit() { this.loadContenidos(); }

  loadContenidos() {
    this.loading.set(true);
    this.service.getAll({ tipo_contenido: this.filterTipo() || undefined }).subscribe({
      next: res => { this.contenidos.set(res.data); this.loading.set(false); },
      error: ()  => { this.loading.set(false); }
    });
  }

  openCreate() {
    this.editingId.set(null);
    this.form.reset({ tipo_contenido: 'articulo' });
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  openEdit(c: Contenido) {
    this.editingId.set(c.id_contenido);
    this.form.patchValue({ titulo: c.titulo, cuerpo: c.cuerpo, tipo_contenido: c.tipo_contenido });
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  closeModal() { this.showModal.set(false); }

  onSubmit() {
    if (this.form.invalid) { this.form.markAllAsTouched(); return; }
    this.saving.set(true);
    const id   = this.editingId();
    const req$ = id
      ? this.service.update(id, this.form.value)
      : this.service.create(this.form.value);

    req$.subscribe({
      next: (res: any) => {
        this.saving.set(false);
        this.showModal.set(false);
        this.successMsg.set(res.message);
        this.loadContenidos();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: (err: any) => {
        this.saving.set(false);
        this.errorMsg.set(err.error?.message ?? 'Error al guardar');
      }
    });
  }

  confirmDelete(c: Contenido) {
    this.deletingId.set(c.id_contenido);
    this.deletingTitulo.set(c.titulo);
    this.showConfirm.set(true);
  }

  cancelDelete() { this.showConfirm.set(false); }

  doDelete() {
    const id = this.deletingId();
    if (!id) return;
    this.service.delete(id).subscribe({
      next: (res: any) => {
        this.showConfirm.set(false);
        this.successMsg.set(res.message);
        this.loadContenidos();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: (err: any) => {
        this.showConfirm.set(false);
        this.errorMsg.set(err.error?.message ?? 'Error al eliminar');
      }
    });
  }

  onFilterTipo(e: Event) { this.filterTipo.set((e.target as HTMLSelectElement).value); this.loadContenidos(); }

  getTipoLabel(tipo: string): string {
    return this.tipos.find(t => t.value === tipo)?.label ?? tipo;
  }

  formatFecha(fecha: string): string {
    return new Date(fecha).toLocaleDateString('es-EC', { day: 'numeric', month: 'short', year: 'numeric' });
  }

  getResumen(cuerpo: string): string {
    return cuerpo.length > 100 ? cuerpo.slice(0, 100) + '...' : cuerpo;
  }

  get titulo() { return this.form.get('titulo'); }
  get cuerpo()  { return this.form.get('cuerpo'); }
}