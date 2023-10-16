import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Response, User } from '../interface/all';
import { environment } from 'src/environments/environment.development';
import { Info, Utilisateur } from '../interface/auth.interface';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
 user:User|null
  constructor(private _http:HttpClient) { 
    this.user=this.getUser();
  }

  login(infoUser:Info):Observable<Response<Utilisateur>>
  {
    return this._http.post<Response<Utilisateur>>(`${environment.api}user/login`,infoUser);
  }

  logout():Observable<Response<Utilisateur>>
  {
    return this._http.get<Response<Utilisateur>>(`${environment.api}/user/logouut`)
  }
  get token()
  {
    return localStorage.getItem('token');
  }
  // private getUser() :User|null
  // {
  //   return JSON.parse(atob(localStorage.getItem('user')?.toString()!)) as User;
  // }
  private getUser(): User | null {
    const encodedUser = localStorage.getItem('user');
    if (encodedUser && encodedUser.trim() !== '') {
      try {   
        const decodedUser = JSON.parse(atob(encodedUser)) as User;
        return decodedUser;
      } catch (error) {
        console.error('Erreur lors du décodage de la chaîne Base64 ou du parsing JSON :', error);
      }
    } else {
      console.warn('La chaîne encodée dans le local storage est vide ou non définie.');
    }
    return null;
  }
  estConnecter()
  {
    return  !!localStorage.getItem('token')
  }
}
