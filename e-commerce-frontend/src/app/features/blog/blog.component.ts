import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { NavbarComponent } from '../../shared/navbar/navbar.component';
import { ContenidoService } from '../../features/contenido/services/contenido.service';
import { Contenido } from '../../features/contenido/models/contenido.model';

@Component({
  selector: 'app-blog',
  standalone: true,
  imports: [CommonModule, NavbarComponent],
  templateUrl: './blog.component.html',
  styleUrls: ['./blog.component.scss']
})
export class BlogComponent implements OnInit {
  private contenidoService = inject(ContenidoService);
  private sanitizer        = inject(DomSanitizer);

  articulos    = signal<Contenido[]>([]);
  loading      = signal(true);
  errorMsg     = signal('');
  filterTipo   = signal('');
  expandedId   = signal<number | null>(null);

  // Video principal fijo
  readonly videoUrl: SafeResourceUrl = this.sanitizer.bypassSecurityTrustResourceUrl(
    'https://www.youtube.com/embed/kuLpv3f7IHc?rel=0&modestbranding=1'
  );

  tiposFiltro = [
    { value: '',          label: 'Todo' },
    { value: 'articulo',  label: 'Artículos' },
    { value: 'noticia',   label: 'Noticias' },
    { value: 'video',     label: 'Videos' },
    { value: 'anuncio',   label: 'Anuncios' },
  ];

  ngOnInit() { this.loadContenido(); }

  loadContenido() {
    this.loading.set(true);
    this.contenidoService.getAll({ tipo_contenido: this.filterTipo() || undefined }).subscribe({
      next: res => { this.articulos.set(res.data); this.loading.set(false); },
      error: ()  => { this.errorMsg.set('Error al cargar el blog'); this.loading.set(false); }
    });
  }

  onFilterTipo(tipo: string) { this.filterTipo.set(tipo); this.loadContenido(); }

  toggleExpand(id: number) {
    this.expandedId.update(v => v === id ? null : id);
  }

  isExpanded(id: number) { return this.expandedId() === id; }

  formatFecha(fecha: string): string {
    return new Date(fecha).toLocaleDateString('es-EC', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  getTipoLabel(tipo: string): string {
    const map: Record<string, string> = {
      articulo: 'Artículo', noticia: 'Noticia', video: 'Video', anuncio: 'Anuncio'
    };
    return map[tipo] ?? tipo;
  }

  getImagen(c: Contenido): string {
    const imgs = [
      'https://imgs.search.brave.com/ZmL-B41YBXveDazx_P_MbnbRNNiRA7WvmYJDiXU0uog/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9oaXBz/LmhlYXJzdGFwcHMu/Y29tL2htZy1wcm9k/L2ltYWdlcy9ibGFu/Y2EtNjlhNDA0NjUy/MmJlNi5qcGVnP2Ny/b3A9MC4zNDJ4dzow/LjUxM3hoOzAuNDU3/eHcsMC4xMjh4aCZy/ZXNpemU9NjQwOio',
    ];
    return imgs[c.id_contenido % imgs.length];
  }

  getResumen(cuerpo: string, maxLen = 160): string {
    if (cuerpo.length <= maxLen) return cuerpo;
    return cuerpo.slice(0, maxLen).trimEnd() + '...';
  }
}