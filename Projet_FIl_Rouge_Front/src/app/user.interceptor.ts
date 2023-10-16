import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HTTP_INTERCEPTORS
} from '@angular/common/http';
import { Observable } from 'rxjs';
import { AuthService } from './service/auth.service';

@Injectable()
export class UserInterceptor implements HttpInterceptor
{

  constructor(private service:AuthService) {}

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    request= request.clone(
    {
      headers:request.headers.set('Authorization' , `Bearer ${this.service.token}`)
    })
    return next.handle(request);
  }
}
export  const ProviderInterceptor=
{
  provide:HTTP_INTERCEPTORS,
  useClass:UserInterceptor,
  multi:true
}
