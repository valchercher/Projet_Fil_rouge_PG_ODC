import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { AllSession, RequestSession, demandeAnuuler } from '../interface/session.interface';
import { environment } from 'src/environments/environment.development';
import { Response } from '../interface/all';

@Injectable({
  providedIn: 'root'
})
export class SessionService {

  constructor( private _http:HttpClient) { }

  storeSessions(data:RequestSession):Observable<Response<AllSession>>{
    return this._http.post<Response<AllSession>>(`${environment.api}create/session`,data);
  }
  demandeAnnulerSession(id:number,data:demandeAnuuler):Observable<Response<AllSession>>
  {
    return this._http.post<Response<AllSession>>(`${environment.api}demandeAnnuler/session/${id}`,data)
  }
  getDemandeAnnulation():Observable<Response<AllSession>>
  {
    return this._http.get<Response<AllSession>>(`${environment.api}demandeAnulation/session`)
  }
  annulerSession(id:number):Observable<Response<AllSession>>
  {
    return this._http.get<Response<AllSession>>(`${environment.api}annulerSession/session/${id}`)
  }
  annulerDemande(id:number):Observable<Response<AllSession>>
  {
    return this._http.get<Response<AllSession>>(`${environment.api}NePasAnnulerSeesion/session/${id}`)
  }
  validerSession(id:number):Observable<Response<AllSession>>
  {
    return this._http.get<Response<AllSession>>(`${environment.api}validerSession/session/${id}`)
  }
}
