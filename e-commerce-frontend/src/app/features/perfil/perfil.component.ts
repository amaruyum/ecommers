import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { AuthService } from '../auth/services/auth.services';


type Tab = 'resumen' | 'reservas' | 'favoritos' | 'configuracion';

@Component({
  selector: 'app-perfil',
  standalone: true,
  imports: [CommonModule, RouterLink, NavbarComponent],
  templateUrl: './perfil.component.html',
  styleUrls: ['./perfil.component.scss']
})
export class PerfilComponent {
  private auth = inject(AuthService);

  usuario  = this.auth.getUsuario();
  activeTab = signal<Tab>('resumen');

  tabs: { key: Tab; label: string; icon: string }[] = [
    { key: 'resumen',        label: 'Resumen',       icon: 'user' },
    //{ key: 'reservas',       label: 'Mis Reservas',  icon: 'calendar' },
    //{ key: 'favoritos',      label: 'Favoritos',     icon: 'heart' },
    //{ key: 'configuracion',  label: 'Configuración', icon: 'settings' },
  ];

  stats = [
    { key: 'reservas',    label: 'Reservas',    count: 3, sub: '2 próximas',          color: '#7C9A7E', icon: 'calendar' },
    { key: 'favoritos',   label: 'Favoritos',   count: 2, sub: 'productos guardados', color: '#C97B4B', icon: 'heart' },
    { key: 'completadas', label: 'Completadas', count: 1, sub: 'experiencias vividas', color: '#8A8A8A', icon: 'clock' },
  ];

  getInitials(): string {
    if (!this.usuario?.nombre_completo) return 'W';
    return this.usuario.nombre_completo
      .split(' ')
      .map((n: string) => n[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();
  }

  setTab(tab: Tab) {
    this.activeTab.set(tab);
  }
}