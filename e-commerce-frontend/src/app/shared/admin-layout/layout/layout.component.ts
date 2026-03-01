import { Component, signal, inject, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, RouterLinkActive, RouterOutlet } from '@angular/router';
import { AuthService } from '../../../features/auth/services/auth.services';


interface NavItem {
  label:    string;
  path?:    string;
  icon:     string;
  badge?:   number;
  badgeColor?: string;
  children?: { label: string; path: string }[];
}

@Component({
  selector: 'app-admin-layout',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, RouterOutlet],
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.scss']
})
export class AdminLayoutComponent {
  private auth   = inject(AuthService);
  private router = inject(Router);

  sidebarCollapsed = signal(false);
  expandedItem     = signal<string | null>(null);
  usuario          = this.auth.getUsuario();

  navItems: NavItem[] = [
    {
      label: 'Dashboard',
      path:  '/admin',
      icon:  'dashboard',
    },
    {
      label: 'Gestión de Usuarios',
      icon:  'users',
      children: [
        { label: 'Todos los Usuarios', path: '/admin/usuarios' },
      ]
    },
    {
      label: 'Gestión de Catálogo',
      icon:  'catalog',
      children: [
        { label: 'Categorías', path: '/admin/catalogo/categorias' },
        { label: 'Items',      path: '/admin/catalogo/items' },
      ]
    },
    {
      label: 'Gestion de contenido',
      icon: 'content',
      children:[
        { label: 'Blog', path: '/admin/contenido/blog'}
      ]
    },
    /*{
      label:     'Gestión de Inventario',
      path:      '/admin/inventario',
      icon:      'inventory',
      badge:     3,
      badgeColor: 'red',
    },
    {
      label: 'Reportes',
      icon:  'reports',
      children: [
        { label: 'Ventas',   path: '/admin/reportes/ventas' },
        { label: 'Pedidos',  path: '/admin/reportes/pedidos' },
      ]
    },
    {
      label: 'Promociones y Cupones',
      path:  '/admin/cupones',
      icon:  'coupons',
    },
    {
      label:     'Notificaciones',
      path:      '/admin/notificaciones',
      icon:      'bell',
      badge:     12,
      badgeColor: 'blue',
    },
    {
      label: 'Configuración',
      path:  '/admin/configuracion',
      icon:  'settings',
    },*/
  ];

  toggleSidebar()   { this.sidebarCollapsed.update(v => !v); }

  toggleExpand(label: string) {
    this.expandedItem.update(v => v === label ? null : label);
  }

  isExpanded(label: string) { return this.expandedItem() === label; }

  logout() {
    this.auth.logout();
    this.router.navigate(['/auth/login']);
  }

  getInitials(): string {
    if (!this.usuario?.nombre_completo) return 'A';
    return this.usuario.nombre_completo
      .split(' ').map((n: string) => n[0]).slice(0, 2).join('').toUpperCase();
  }
}