import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Item, ItemResponse } from '../models/item.model';
import { AuthService } from '../../auth/services/auth.services';


@Injectable({ providedIn: 'root' })
export class ItemService {
  private http   = inject(HttpClient);
  private auth   = inject(AuthService);
  private apiUrl = 'http://localhost:8000/api';

  private headers(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type':  'application/json',
      'Authorization': `Bearer ${this.auth.getToken()}`,
    });
  }

  getAll(filters?: { search?: string; estado?: string; id_categoria?: number; tipo?: string }): Observable<ItemResponse> {
    let params = new HttpParams();
    if (filters?.search)       params = params.set('search',       filters.search);
    if (filters?.estado)       params = params.set('estado',       filters.estado);
    if (filters?.id_categoria) params = params.set('id_categoria', filters.id_categoria.toString());
    if (filters?.tipo)         params = params.set('tipo',         filters.tipo);

    return this.http.get<ItemResponse>(`${this.apiUrl}/items`, { headers: this.headers(), params });
  }

  getById(id: number): Observable<Item> {
    return this.http.get<Item>(`${this.apiUrl}/items/${id}`, { headers: this.headers() });
  }

  create(data: any): Observable<{ message: string; item: Item }> {
    return this.http.post<{ message: string; item: Item }>(`${this.apiUrl}/items`, data, { headers: this.headers() });
  }

  update(id: number, data: any): Observable<{ message: string; item: Item }> {
    return this.http.put<{ message: string; item: Item }>(`${this.apiUrl}/items/${id}`, data, { headers: this.headers() });
  }

  delete(id: number): Observable<{ message: string }> {
    return this.http.delete<{ message: string }>(`${this.apiUrl}/items/${id}`, { headers: this.headers() });
  }
}