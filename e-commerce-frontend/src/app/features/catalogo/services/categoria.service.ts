import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Categoria, CategoriaForm, CategoriaResponse } from '../models/categoria.model';
import { AuthService } from '../../auth/services/auth.services';


@Injectable({ providedIn: 'root' })
export class CategoriaService {
  private http    = inject(HttpClient);
  private auth    = inject(AuthService);
  private apiUrl  = 'http://localhost:8000/api';

  private headers(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type':  'application/json',
      'Authorization': `Bearer ${this.auth.getToken()}`,
    });
  }

  getAll(filters?: { tipo?: string; estado?: string; search?: string }): Observable<CategoriaResponse> {
    let params = new HttpParams();
    if (filters?.tipo)   params = params.set('tipo',   filters.tipo);
    if (filters?.estado) params = params.set('estado', filters.estado);
    if (filters?.search) params = params.set('search', filters.search);

    return this.http.get<CategoriaResponse>(`${this.apiUrl}/categorias`, {
      headers: this.headers(), params
    });
  }

  getById(id: number): Observable<Categoria> {
    return this.http.get<Categoria>(`${this.apiUrl}/categorias/${id}`, {
      headers: this.headers()
    });
  }

  create(data: CategoriaForm): Observable<{ message: string; categoria: Categoria }> {
    return this.http.post<{ message: string; categoria: Categoria }>(
      `${this.apiUrl}/categorias`, data, { headers: this.headers() }
    );
  }

  update(id: number, data: CategoriaForm): Observable<{ message: string; categoria: Categoria }> {
    return this.http.put<{ message: string; categoria: Categoria }>(
      `${this.apiUrl}/categorias/${id}`, data, { headers: this.headers() }
    );
  }

  delete(id: number): Observable<{ message: string }> {
    return this.http.delete<{ message: string }>(
      `${this.apiUrl}/categorias/${id}`, { headers: this.headers() }
    );
  }
}