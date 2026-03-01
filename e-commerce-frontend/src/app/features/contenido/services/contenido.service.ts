import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Contenido, ContenidoResponse } from '../models/contenido.model';
import { AuthService } from '../../auth/services/auth.services';


@Injectable({ providedIn: 'root' })
export class ContenidoService {
  private http   = inject(HttpClient);
  private auth   = inject(AuthService);
  private apiUrl = 'http://localhost:8000/api';

  private headers(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type':  'application/json',
      'Authorization': `Bearer ${this.auth.getToken()}`,
    });
  }

  getAll(filters?: { tipo_contenido?: string; search?: string }): Observable<ContenidoResponse> {
    let params = new HttpParams();
    if (filters?.tipo_contenido) params = params.set('tipo_contenido', filters.tipo_contenido);
    if (filters?.search)         params = params.set('search', filters.search);
    return this.http.get<ContenidoResponse>(`${this.apiUrl}/contenido`, { headers: this.headers(), params });
  }

  create(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/contenido`, data, { headers: this.headers() });
  }

  update(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/contenido/${id}`, data, { headers: this.headers() });
  }

  delete(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/contenido/${id}`, { headers: this.headers() });
  }
}