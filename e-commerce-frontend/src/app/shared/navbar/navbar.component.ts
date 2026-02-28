import { Component, inject, signal, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../features/auth/services/auth.services';



@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive],
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent {
  private auth   = inject(AuthService);
  private router = inject(Router);

  dropdownOpen = signal(false);
  usuario      = this.auth.getUsuario();

  navLinks = [
    { label: 'Inicio',            path: '/dashboard' },
    { label: 'Retiros/Eventos',   path: '/dashboard/retiros' },
    { label: 'Capacitaciones',    path: '/dashboard/capacitaciones' },
    { label: 'Productos',         path: '/dashboard/productos' },
    { label: 'Blog',              path: '/dashboard/blog' },
  ];

  toggleDropdown() {
    this.dropdownOpen.update(v => !v);
  }

  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent) {
    const target = event.target as HTMLElement;
    if (!target.closest('.user-menu')) {
      this.dropdownOpen.set(false);
    }
  }

  logout() {
    this.auth.logout();
    this.router.navigate(['/auth/login']);
  }

  getInitials(): string {
    if (!this.usuario?.nombre_completo) return 'W';
    return this.usuario.nombre_completo
      .split(' ')
      .map((n: string) => n[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();
  }
}