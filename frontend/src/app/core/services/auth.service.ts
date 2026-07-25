import { computed, inject, Injectable, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { User } from '../models/user.model';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private http = inject(HttpClient);
  private router = inject(Router);

  currentUser = signal<User | null>(null);
  isAuthenticated = computed(() => !!this.currentUser());

  loadUser() {
    this.http.get<{user: User}>('/api/user').subscribe({
      next: (res) => this.currentUser.set(res.user),
      error: () => this.currentUser.set(null),
    });
  }

  register(data: { name: string; email: string; password: string; password_confirmation: string }) {
    return this.http.post<{ user: User }>('/api/register', data);
  }

  login(data: { email: string; password: string }) {
    return this.http.post<{ user: User }>('/api/login', data);
  }

  logout() {
    this.http.post('/api/logout', {}).subscribe(() => {
      this.currentUser.set(null);
      this.router.navigate(['/login']);
    });
  }
}
