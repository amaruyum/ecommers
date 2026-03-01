import { Component, signal, HostListener, ElementRef, inject } from '@angular/core';
import { CommonModule } from '@angular/common';

export interface Notificacion {
  id:       number;
  tipo:     'pedido' | 'retiro' | 'anuncio' | 'sistema';
  titulo:   string;
  mensaje:  string;
  tiempo:   string;
  leida:    boolean;
}

@Component({
  selector: 'app-notificaciones',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './notificaciones.component.html',
  styleUrls: ['./notificaciones.component.scss']
})
export class NotificacionesComponent {
  private el = inject(ElementRef);

  open = signal(false);

  notificaciones = signal<Notificacion[]>([
    {
      id: 1,
      tipo: 'pedido',
      titulo: 'Pedido confirmado',
      mensaje: 'Tu pedido #1042 fue recibido. Te contactaremos pronto para coordinar la entrega.',
      tiempo: 'Hace 5 min',
      leida: false,
    },
    {
      id: 2,
      tipo: 'pedido',
      titulo: 'Pedido en camino',
      mensaje: 'Tu pedido #1039 de Kit de Aceites Esenciales está en camino.',
      tiempo: 'Hace 2 horas',
      leida: false,
    },
    {
      id: 3,
      tipo: 'retiro',
      titulo: 'Nuevas fechas disponibles',
      mensaje: 'Se abrieron nuevos cupos para el retiro "Yoga y Silencio en la Montaña".',
      tiempo: 'Ayer',
      leida: true,
    },
    {
      id: 4,
      tipo: 'anuncio',
      titulo: '20% de descuento en abril',
      mensaje: 'Celebra el Día de la Tierra con descuento en todos los retiros. Código: TIERRA2026.',
      tiempo: 'Hace 3 días',
      leida: true,
    },
  ]);

  sinLeer = () => this.notificaciones().filter(n => !n.leida).length;

  toggle() { this.open.update(v => !v); }

  marcarLeida(id: number) {
    this.notificaciones.update(list =>
      list.map(n => n.id === id ? { ...n, leida: true } : n)
    );
  }

  marcarTodasLeidas() {
    this.notificaciones.update(list => list.map(n => ({ ...n, leida: true })));
  }

  getIcono(tipo: Notificacion['tipo']): string {
    const map: Record<string, string> = {
      pedido:  '🛍️',
      retiro:  '🌿',
      anuncio: '📢',
      sistema: '⚙️',
    };
    return map[tipo] ?? '🔔';
  }

  @HostListener('document:click', ['$event'])
  onClickOutside(e: MouseEvent) {
    if (!this.el.nativeElement.contains(e.target)) {
      this.open.set(false);
    }
  }

  @HostListener('document:keydown.escape')
  onEsc() { this.open.set(false); }
}