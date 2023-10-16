import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { AuthentificationComponent } from './authentification/authentification.component';



@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    RouterModule.forChild([
      {path:'login',component:AuthentificationComponent},
      {path:"**",component:AuthentificationComponent}
    ])
  ]
})
export class PublicModule { }
