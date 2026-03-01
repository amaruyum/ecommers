import { Component, inject, signal, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { FormsModule } from '@angular/forms';

import { ItemService } from '../../features/catalogo/services/item.service';
import { ContenidoService } from '../../features/contenido/services/contenido.service';
import { Item } from '../../features/catalogo/models/item.model';
import { Contenido } from '../../features/contenido/models/contenido.model';
import { debounceTime, distinctUntilChanged, Subject, switchMap, forkJoin, of } from 'rxjs';
import { CartDrawerComponent } from '../../features/cart/cart.component';
import { AuthService } from '../../features/auth/services/auth.services';
import { CartService } from '../../features/cart/services/cart.service';

export interface SearchResult {
  tipo:       'item' | 'blog';
  id:          number;
  titulo:      string;
  subtitulo:   string;
  path:        string;
  badge:       string;
  badgeClass:  string;
}

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule, CartDrawerComponent],
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent {
  private auth             = inject(AuthService);
  private router           = inject(Router);
  private itemService      = inject(ItemService);
  private contenidoService = inject(ContenidoService);
  cart                     = inject(CartService);

  dropdownOpen  = signal(false);
  searchOpen    = signal(false);
  searchQuery   = signal('');
  searchLoading = signal(false);
  resultados    = signal<SearchResult[]>([]);
  sinResultados = signal(false);

  usuario = this.auth.getUsuario();
  isAdmin = this.auth.isAdmin();

  private search$ = new Subject<string>();

  navLinks = [
    { label: 'Inicio',          path: '/dashboard' },
    { label: 'Retiros/Eventos', path: '/dashboard/retiros' },
    { label: 'Capacitaciones',  path: '/dashboard/capacitaciones' },
    { label: 'Productos',       path: '/dashboard/productos' },
    { label: 'Blog',            path: '/dashboard/blog' },
  ];

  constructor() {
    this.search$.pipe(
      debounceTime(350),
      distinctUntilChanged(),
      switchMap(q => {
        if (!q.trim() || q.length < 2) {
          this.resultados.set([]); this.sinResultados.set(false); this.searchLoading.set(false);
          return of(null);
        }
        this.searchLoading.set(true);
        return forkJoin({
          items:     this.itemService.getAll({ search: q, estado: 'activo' }),
          contenido: this.contenidoService.getAll({ search: q }),
        });
      })
    ).subscribe(res => {
      if (!res) return;
      this.searchLoading.set(false);
      const resultados: SearchResult[] = [];
      res.items.data.slice(0, 5).forEach((item: Item) => {
        const tipoLabel: Record<string, string> = { retiro:'Retiro', capacitacion:'Capacitación', taller:'Taller', clase:'Clase', evento:'Evento' };
        const badge = item.tipo === 'producto' ? 'Producto' : (tipoLabel[item.servicio?.tipo_servicio ?? ''] ?? 'Servicio');
        resultados.push({ tipo: 'item', id: item.id_item, titulo: item.nombre, subtitulo: item.descripcion ?? '', path: `/dashboard/item/${item.id_item}`, badge, badgeClass: item.tipo === 'producto' ? 'badge-prod' : 'badge-serv' });
      });
      res.contenido.data.slice(0, 3).forEach((c: Contenido) => {
        const tipoMap: Record<string,string> = { articulo:'Artículo', noticia:'Noticia', video:'Video', anuncio:'Anuncio' };
        resultados.push({ tipo:'blog', id: c.id_contenido, titulo: c.titulo, subtitulo: c.cuerpo.slice(0,80)+'...', path:'/dashboard/blog', badge: tipoMap[c.tipo_contenido] ?? c.tipo_contenido, badgeClass:'badge-blog' });
      });
      this.resultados.set(resultados);
      this.sinResultados.set(resultados.length === 0);
    });
  }

  openSearch() {
    this.searchOpen.set(true); this.searchQuery.set(''); this.resultados.set([]); this.sinResultados.set(false);
    setTimeout(() => document.getElementById('global-search-input')?.focus(), 50);
  }
  closeSearch() { this.searchOpen.set(false); this.searchQuery.set(''); this.resultados.set([]); }
  onSearchInput(q: string) { this.searchQuery.set(q); this.search$.next(q); }
  goToResult(path: string) { this.closeSearch(); this.router.navigate([path]); }
  toggleDropdown() { this.dropdownOpen.update(v => !v); }

  @HostListener('document:keydown.escape')
  onEscape() { if (this.searchOpen()) this.closeSearch(); if (this.dropdownOpen()) this.dropdownOpen.set(false); }

  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent) { const t = event.target as HTMLElement; if (!t.closest('.user-menu')) this.dropdownOpen.set(false); }

  logout() { this.auth.logout(); this.router.navigate(['/auth/login']); }

  getInitials(): string {
    if (!this.usuario?.nombre_completo) return 'W';
    return this.usuario.nombre_completo.split(' ').map((n: string) => n[0]).slice(0, 2).join('').toUpperCase();
  }
}