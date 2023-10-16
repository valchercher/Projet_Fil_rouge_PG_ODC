import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { All, ExcelUsers, RequestCours, Response } from '../interface/all';
import { environment } from 'src/environments/environment.development';
import { Observable, throwError } from 'rxjs';
import { AllSession } from '../interface/session.interface';
import { Utilisateur } from '../interface/auth.interface';

@Injectable({
  providedIn: 'root'
})
export class AllServiceService {

  constructor(private _http:HttpClient) { }
  index(pageSize:number,page?:number):Observable<Response<All>>{
    return this._http.get<Response<All>>(`${environment.api}index/${pageSize}?page=${page}`)
    // .pipe(
    //   tap(reponse=>console.log(reponse)
    //   ),
    //   catchError(this.handleError)
    // )
  }
  storeCours(data:RequestCours):Observable<Response<All>>
  {
    return this._http.post<Response<All>>(`${environment.api}create/cours`,data);
  }
  indexsession(role:string,cle?:string,libelle?:string):Observable<Response<AllSession>>
  {
    return this._http.get<Response<AllSession>>(`${environment.api}session/${role}/${cle}/${libelle}`);
  }
  load(role:string,index:number,cle?:string,libelle?:string,page?:number):Observable<Response<All>>
  {
    return this._http.get<Response<All>>(`${environment.api}cours/${role}/${index}/${cle}/${libelle}?page=${page}`)
  }
  inscription(data:ExcelUsers):Observable<Response<Utilisateur>>
  {   
    return this._http.post<Response<Utilisateur>>(`${environment.api}user/inscription`,data)
  }
  private handleError(error: HttpErrorResponse) {
    if (error.status === 0) {
      console.error('An error occurred:', error.error);
    } else {
      console.error(
        `Backend returned code ${error.status}, body was: `, error.error);
    }
    return throwError(() => new Error('Something bad happened; please try again later.'));
  };

}
