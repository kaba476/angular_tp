import { HttpErrorResponse, HttpInterceptorFn } from '@angular/common/http';
import { catchError, throwError } from 'rxjs';

export const errorInterceptor: HttpInterceptorFn = (req, next) => {
  return next(req).pipe(
    catchError((err: HttpErrorResponse) => {
      const message =
        err.error?.message || err.error?.email?.[0] || err.message || 'Une erreur est survenue';
      console.error(message);
      return throwError(() => err);
    })
  );
};
