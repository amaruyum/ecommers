import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Usuario, UsuarioResponse } from '../models/usuario.model';
import { AuthService } from '../../auth/services/auth.services';


@Injectable({ providedIn: 'root' })
export class UsuarioService {
  private http   = inject(HttpClient);
  private auth   = inject(AuthService);
  private apiUrl = 'http://localhost:8000/api';

  private headers(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type':  'application/json',
      'Authorization': `Bearer ${this.auth.getToken()}`,
    });
  }

  getAll(filters?: { search?: string; estado_cuenta?: string; rol?: string }): Observable<UsuarioResponse> {
    let params = new HttpParams();
    if (filters?.search)       params = params.set('search',       filters.search);
    if (filters?.estado_cuenta) params = params.set('estado_cuenta', filters.estado_cuenta);
    if (filters?.rol)          params = params.set('rol',          filters.rol);
    return this.http.get<UsuarioResponse>(`${this.apiUrl}/usuarios`, { headers: this.headers(), params });
  }

  create(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/usuarios`, data, { headers: this.headers() });
  }

  update(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/usuarios/${id}`, data, { headers: this.headers() });
  }

  delete(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/usuarios/${id}`, { headers: this.headers() });
  }
}