import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { ItemService } from '../../features/catalogo/services/item.service';
import { Item } from '../../features/catalogo/models/item.model';

@Component({
  selector: 'app-retiros',
  standalone: true,
  imports: [CommonModule, RouterLink, NavbarComponent],
  templateUrl: './retiros.component.html',
  styleUrls: ['./retiros.component.scss']
})
export class RetirosComponent implements OnInit {
  private itemService = inject(ItemService);

  retiros    = signal<Item[]>([]);
  loading    = signal(true);
  errorMsg   = signal('');
  favoritos  = signal<number[]>([]);

  // Filtros
  filterSearch = signal('');
  filterEstado = signal('activo');

  ngOnInit() { this.loadRetiros(); }

  loadRetiros() {
    this.loading.set(true);
    this.itemService.getAll({ tipo: 'servicio', estado: this.filterEstado() || undefined, search: this.filterSearch() || undefined }).subscribe({
      next: res => {
        this.retiros.set(res.data);
        this.loading.set(false);
      },
      error: () => {
        this.errorMsg.set('Error al cargar los retiros');
        this.loading.set(false);
      }
    });
  }

  getDestacados() { return this.retiros().slice(0, 2); }
  getTodos()      { return this.retiros(); }

  toggleFavorito(id: number) {
    this.favoritos.update(favs =>
      favs.includes(id) ? favs.filter(f => f !== id) : [...favs, id]
    );
  }

  esFavorito(id: number): boolean { return this.favoritos().includes(id); }

  onSearch(e: Event) {
    this.filterSearch.set((e.target as HTMLInputElement).value);
    this.loadRetiros();
  }

  getTipoLabel(item: Item): string {
    const tipo = item.servicio?.tipo_servicio;
    const map: Record<string, string> = {
      retiro: 'Retiro', capacitacion: 'Capacitación',
      taller: 'Taller', clase: 'Clase', evento: 'Evento'
    };
    return tipo ? (map[tipo] ?? tipo) : 'Servicio';
  }

  getNivelLabel(item: Item): string {
    // Por ahora todos los niveles — se puede extender con campo propio
    return 'Todos los niveles';
  }

  formatFecha(fecha: string | null): string {
    if (!fecha) return '—';
    return new Date(fecha).toLocaleDateString('es-EC', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  getDuracion(item: Item): string {
    if (!item.servicio?.fecha_inicio || !item.servicio?.fecha_fin) return '—';
    const inicio = new Date(item.servicio.fecha_inicio);
    const fin    = new Date(item.servicio.fecha_fin);
    const dias   = Math.ceil((fin.getTime() - inicio.getTime()) / (1000 * 60 * 60 * 24));
    return `${dias} días / ${dias - 1} noches`;
  }

  // Imagen placeholder según tipo de servicio
  getImagen(item: Item): string {
    const placeholders = [
      'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&q=80',
      'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?w=800&q=80',
      'https://images.unsplash.com/photo-1518241353330-0f7941c2d9b5?w=800&q=80',
      'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&q=80',
    ];
    return placeholders[item.id_item % placeholders.length];
  }
}