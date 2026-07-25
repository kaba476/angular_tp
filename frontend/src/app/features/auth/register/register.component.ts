import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-register',
  imports: [FormsModule, RouterLink],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css',
})
export class RegisterComponent {
  private auth = inject(AuthService);
  private router = inject(Router);

  user = { name: '', email: '', password: '', password_confirmation: '' };
  error = '';

  register() {
    this.auth.register(this.user).subscribe({
      next: (res) => {
        this.auth.currentUser.set(res.user);
        this.router.navigate(['/tasks']);
      },
      error: (err) => {
        this.error = err.error?.message || 'Échec de l\'inscription';
      },
    });
  }
}
