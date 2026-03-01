import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { ItemService } from '../../features/catalogo/services/item.service';
import { CategoriaService } from '../../features/catalogo/services/categoria.service';
import { Item } from '../../features/catalogo/models/item.model';
import { Categoria } from '../../features/catalogo/models/categoria.model';

@Component({
  selector: 'app-capacitaciones',
  standalone: true,
  imports: [CommonModule, RouterLink, NavbarComponent],
  templateUrl: './capacitaciones.component.html',
  styleUrls: ['./capacitaciones.component.scss']
})
export class CapacitacionesComponent implements OnInit {
  private itemService      = inject(ItemService);
  private categoriaService = inject(CategoriaService);

  capacitaciones = signal<Item[]>([]);
  categorias     = signal<Categoria[]>([]);
  loading        = signal(true);
  errorMsg       = signal('');
  favoritos      = signal<number[]>([]);

  filterSearch    = signal('');
  filterCategoria = signal('');
  filterTipo      = signal('');

  tiposServicio = [
    { value: '',              label: 'Todos los tipos' },
    { value: 'capacitacion',  label: 'Capacitación' },
    { value: 'taller',        label: 'Taller' },
    { value: 'clase',         label: 'Clase' },
    { value: 'evento',        label: 'Evento' },
  ];

  ngOnInit() {
    this.loadCapacitaciones();
    this.loadCategorias();
  }

  loadCapacitaciones() {
    this.loading.set(true);
    this.itemService.getAll({
      tipo:         'servicio',
      estado:       'activo',
      search:       this.filterSearch() || undefined,
      id_categoria: this.filterCategoria() ? +this.filterCategoria() : undefined,
    }).subscribe({
      next: res => {
        // Filtrar por tipo de servicio si aplica
        let data = res.data;
        if (this.filterTipo()) {
          data = data.filter(i => i.servicio?.tipo_servicio === this.filterTipo());
        } else {
          // Excluir retiros para que no se dupliquen
          data = data.filter(i => i.servicio?.tipo_servicio !== 'retiro');
        }
        this.capacitaciones.set(data);
        this.loading.set(false);
      },
      error: () => { this.errorMsg.set('Error al cargar capacitaciones'); this.loading.set(false); }
    });
  }

  loadCategorias() {
    this.categoriaService.getAll({ estado: 'activo' }).subscribe({
      next: res => this.categorias.set(res.data)
    });
  }

  toggleFavorito(id: number) {
    this.favoritos.update(favs =>
      favs.includes(id) ? favs.filter(f => f !== id) : [...favs, id]
    );
  }

  esFavorito(id: number) { return this.favoritos().includes(id); }

  onSearch(e: Event)    { this.filterSearch.set((e.target as HTMLInputElement).value);   this.loadCapacitaciones(); }
  onFilterCat(e: Event) { this.filterCategoria.set((e.target as HTMLSelectElement).value); this.loadCapacitaciones(); }
  onFilterTipo(e: Event){ this.filterTipo.set((e.target as HTMLSelectElement).value);    this.loadCapacitaciones(); }

  getTipoLabel(item: Item): string {
    const map: Record<string, string> = {
      capacitacion: 'Capacitación', taller: 'Taller',
      clase: 'Clase', evento: 'Evento', retiro: 'Retiro'
    };
    return map[item.servicio?.tipo_servicio ?? ''] ?? 'Servicio';
  }

  getTipoColor(item: Item): string {
    const map: Record<string, string> = {
      capacitacion: 'tipo-cap', taller: 'tipo-taller',
      clase: 'tipo-clase', evento: 'tipo-evento'
    };
    return map[item.servicio?.tipo_servicio ?? ''] ?? 'tipo-cap';
  }

  formatFecha(fecha: string | null): string {
    if (!fecha) return '—';
    return new Date(fecha).toLocaleDateString('es-EC', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  getDuracion(item: Item): string {
    if (!item.servicio?.fecha_inicio || !item.servicio?.fecha_fin) return '—';
    const inicio = new Date(item.servicio.fecha_inicio);
    const fin    = new Date(item.servicio.fecha_fin);
    const horas  = Math.round((fin.getTime() - inicio.getTime()) / (1000 * 60 * 60));
    if (horas < 24) return `${horas} horas`;
    const dias = Math.ceil(horas / 24);
    return `${dias} día${dias > 1 ? 's' : ''}`;
  }

  getCuposInfo(item: Item): { disponibles: number; total: number; porcentaje: number } {
    const disponibles = item.servicio?.cupos_disponibles ?? 0;
    const total       = item.servicio?.cupos_totales ?? 0;
    const porcentaje  = total > 0 ? Math.round((disponibles / total) * 100) : 0;
    return { disponibles, total, porcentaje };
  }

  getImagen(item: Item): string {
    const imgs = [
      'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&q=80',
      'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&q=80',
      'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&q=80',
      'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80',
    ];
    return imgs[item.id_item % imgs.length];
  }
}