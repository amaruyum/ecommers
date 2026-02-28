import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { AuthService } from '../auth/services/auth.services';


interface DashboardCard {
  title:       string;
  description: string;
  path:        string;
  icon:        string;
  bgColor:     string;
  iconColor:   string;
}

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink, NavbarComponent],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent {
  private auth   = inject(AuthService);
  private router = inject(Router);

  usuario = this.auth.getUsuario();

  cards: DashboardCard[] = [
    {
      title:       'Retiros/Eventos',
      description: 'Ven y Descansa',
      path:        '/dashboard/retiros',
      icon:        'retiros',
      bgColor:     '#EEF3EE',
      iconColor:   '#7C9A7E',
    },
    {
      title:       'Capacitaciones',
      description: 'Las mejores capacitaciones',
      path:        '/dashboard/capacitaciones',
      icon:        'capacitaciones',
      bgColor:     '#F5EDE8',
      iconColor:   '#C97B4B',
    },
    {
      title:       'Productos',
      description: 'Observa todos los productos disponibles antes que se agoten',
      path:        '/dashboard/productos',
      icon:        'productos',
      bgColor:     '#F2EDE4',
      iconColor:   '#A8946E',
    },
    {
      title:       'Blog',
      description: 'Mira tus blog mas divertidos',
      path:        '/dashboard/blog',
      icon:        'blog',
      bgColor:     '#EEF3EE',
      iconColor:   '#7C9A7E',
    },
    {
      title:       'Mi Perfil',
      description: 'Ver y editar tu perfil de usuario',
      path:        '/dashboard/perfil',
      icon:        'perfil',
      bgColor:     '#F5EDE8',
      iconColor:   '#C97B4B',
    },
  ];
}