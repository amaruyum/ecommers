import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, ReactiveFormsModule, Validators } from '@angular/forms';
import { UsuarioService } from '../../../features/usuarios/services/usuario.service';
import { Usuario } from '../../../features/usuarios/models/usuario.model';

@Component({
  selector: 'app-admin-usuarios',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './usuarios.component.html',
  styleUrls: ['./usuarios.component.scss']
})
export class AdminUsuariosComponent implements OnInit {
  private fb      = inject(FormBuilder);
  private service = inject(UsuarioService);

  usuarios     = signal<Usuario[]>([]);
  loading      = signal(false);
  saving       = signal(false);
  errorMsg     = signal('');
  successMsg   = signal('');
  showModal    = signal(false);
  showConfirm  = signal(false);
  editingId    = signal<number | null>(null);
  deletingId   = signal<number | null>(null);
  deletingNombre = signal('');

  filterSearch = signal('');
  filterEstado = signal('');
  filterRol    = signal('');

  rolSeleccionado = signal<'cliente' | 'instructor' | 'admin'>('cliente');

  form = this.fb.group({
    nombre_completo:    ['', [Validators.required]],
    correo_electronico: ['', [Validators.required, Validators.email]],
    contrasena:         [''],
    telefono:           [''],
    estado_cuenta:      ['activo', Validators.required],
    rol:                ['cliente', Validators.required],
    // Cliente
    ciudad:       [''],
    direccion:    [''],
    preferencias: [''],
    // Instructor
    especialidad:         [''],
    descripcion_perfil: [''],
  });

  ngOnInit() {
    this.loadUsuarios();
    this.form.get('rol')?.valueChanges.subscribe(v => {
      this.rolSeleccionado.set(v as any);
    });
  }

  loadUsuarios() {
    this.loading.set(true);
    this.service.getAll({
      search:       this.filterSearch(),
      estado_cuenta: this.filterEstado(),
      rol:          this.filterRol(),
    }).subscribe({
      next: res => { this.usuarios.set(res.data); this.loading.set(false); },
      error: ()  => { this.loading.set(false); }
    });
  }

  openCreate() {
    this.editingId.set(null);
    this.form.reset({ estado_cuenta: 'activo', rol: 'cliente' });
    this.rolSeleccionado.set('cliente');
    this.form.get('contrasena')?.setValidators([Validators.required, Validators.minLength(8)]);
    this.form.get('contrasena')?.updateValueAndValidity();
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  openEdit(u: Usuario) {
    this.editingId.set(u.id_usuario);
    const rol = u.roles.includes('admin') ? 'admin' : u.roles.includes('instructor') ? 'instructor' : 'cliente';
    this.rolSeleccionado.set(rol as any);
    this.form.get('contrasena')?.clearValidators();
    this.form.get('contrasena')?.updateValueAndValidity();
    this.form.patchValue({
      nombre_completo:    u.nombre_completo,
      correo_electronico: u.correo_electronico,
      contrasena:         '',
      telefono:           u.telefono ?? '',
      estado_cuenta:      u.estado_cuenta,
      rol,
      ciudad:       u.cliente?.ciudad ?? '',
      direccion:    u.cliente?.direccion ?? '',
      preferencias: u.cliente?.preferencias ?? '',
      especialidad:         u.instructor?.especialidad ?? '',
      descripcion_perfil: u.instructor?.descripcion_perfil ?? '',
    });
    this.errorMsg.set('');
    this.showModal.set(true);
  }

  closeModal() { this.showModal.set(false); this.errorMsg.set(''); }

  onSubmit() {
    if (this.form.invalid) { this.form.markAllAsTouched(); return; }
    this.saving.set(true);
    this.errorMsg.set('');

    const v = this.form.value;
    const payload: any = {
      nombre_completo:    v.nombre_completo,
      correo_electronico: v.correo_electronico,
      telefono:           v.telefono,
      estado_cuenta:      v.estado_cuenta,
      rol:                v.rol,
      ciudad:             v.ciudad,
      direccion:          v.direccion,
      preferencias:       v.preferencias,
      especialidad:         v.especialidad,
      descripcion_perfil: v.descripcion_perfil,
    };
    if (v.contrasena) payload.contrasena = v.contrasena;

    const id   = this.editingId();
    const req$ = id ? this.service.update(id, payload) : this.service.create(payload);

    req$.subscribe({
      next: (res: any) => {
        this.saving.set(false);
        this.showModal.set(false);
        this.successMsg.set(res.message);
        this.loadUsuarios();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: (err: any) => {
        this.saving.set(false);
        if (err.status === 422 && err.error?.errors) {
          this.errorMsg.set(Object.values(err.error.errors).flat().join(', ') as string);
        } else {
          this.errorMsg.set(err.error?.message ?? 'Error al guardar');
        }
      }
    });
  }

  confirmDelete(u: Usuario) {
    this.deletingId.set(u.id_usuario);
    this.deletingNombre.set(u.nombre_completo);
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
        this.loadUsuarios();
        setTimeout(() => this.successMsg.set(''), 3000);
      },
      error: (err: any) => {
        this.showConfirm.set(false);
        this.errorMsg.set(err.error?.message ?? 'Error al eliminar');
        setTimeout(() => this.errorMsg.set(''), 4000);
      }
    });
  }

  onSearch(e: Event)       { this.filterSearch.set((e.target as HTMLInputElement).value); this.loadUsuarios(); }
  onFilterEstado(e: Event) { this.filterEstado.set((e.target as HTMLSelectElement).value); this.loadUsuarios(); }
  onFilterRol(e: Event)    { this.filterRol.set((e.target as HTMLSelectElement).value);   this.loadUsuarios(); }

  getRolBadge(roles: string[]): string {
    if (roles.includes('admin'))      return 'admin';
    if (roles.includes('instructor')) return 'instructor';
    if (roles.includes('cliente'))    return 'cliente';
    return 'usuario';
  }

  getInitials(nombre: string): string {
    return nombre.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
  }

  get nombre()    { return this.form.get('nombre_completo'); }
  get email()     { return this.form.get('correo_electronico'); }
  get password()  { return this.form.get('contrasena'); }
  get esCliente()    { return this.rolSeleccionado() === 'cliente'; }
  get esInstructor() { return this.rolSeleccionado() === 'instructor'; }
}