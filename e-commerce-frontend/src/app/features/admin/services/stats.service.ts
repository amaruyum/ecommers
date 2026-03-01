import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { AuthService } from '../../auth/services/auth.services';

export interface DashboardStats {
  usuarios: {
    total: number; clientes: number; instructores: number; admins: number;
  };
  items: {
    total: number; activos: number; agotados: number; inactivos: number;
    servicios: number; productos: number;
  };
  categorias: { total: number; activas: number };
  items_por_categoria: { nombre: string; total: number }[];
  servicios_proximos: {
    id_item: number; nombre: string; precio: number;
    fecha_inicio: string; lugar: string;
    cupos_disponibles: number; cupos_totales: number;
    tipo_servicio: string; categoria: string;
  }[];
  usuarios_recientes: {
    id_usuario: number; nombre_completo: string;
    correo_electronico: string; estado_cuenta: string; fecha_registro: string;
  }[];
  stock_bajo: {
    id_item: number; nombre: string; stock_disponible: number; marca: string;
  }[];
}

@Injectable({ providedIn: 'root' })
export class StatsService {
  private http   = inject(HttpClient);
  private auth   = inject(AuthService);
  private apiUrl = 'http://localhost:8000/api';

  private headers(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type':  'application/json',
      'Authorization': `Bearer ${this.auth.getToken()}`,
    });
  }

  getDashboard(): Observable<DashboardStats> {
    return this.http.get<DashboardStats>(`${this.apiUrl}/admin/stats`, { headers: this.headers() });
  }
}