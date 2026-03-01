import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { StatsService, DashboardStats } from '../../../features/admin/services/stats.service';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class AdminDashboardComponent implements OnInit {
  private statsService = inject(StatsService);

  stats   = signal<DashboardStats | null>(null);
  loading = signal(true);
  error   = signal('');

  ngOnInit() { this.loadStats(); }

  loadStats() {
    this.loading.set(true);
    this.statsService.getDashboard().subscribe({
      next: data => { this.stats.set(data); this.loading.set(false); },
      error: ()   => { this.error.set('Error al cargar estadísticas'); this.loading.set(false); }
    });
  }

  formatFecha(fecha: string | null): string {
    if (!fecha) return '—';
    return new Date(fecha).toLocaleDateString('es-EC', { day: 'numeric', month: 'short', year: 'numeric' });
  }

  formatFechaCorta(fecha: string | null): string {
    if (!fecha) return '—';
    return new Date(fecha).toLocaleDateString('es-EC', { day: 'numeric', month: 'short' });
  }

  getCuposPct(disp: number, total: number): number {
    if (!total) return 0;
    return Math.round((disp / total) * 100);
  }

  getTipoLabel(tipo: string): string {
    const map: Record<string, string> = {
      retiro: 'Retiro', capacitacion: 'Capacitación',
      taller: 'Taller', clase: 'Clase', evento: 'Evento'
    };
    return map[tipo] ?? tipo;
  }

  getInitials(nombre: string): string {
    return nombre.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
  }

  getBarWidth(value: number, max: number): number {
    if (!max) return 0;
    return Math.round((value / max) * 100);
  }

  getMaxCategoria(): number {
    const cats = this.stats()?.items_por_categoria ?? [];
    return cats.length ? Math.max(...cats.map(c => c.total)) : 1;
  }
}