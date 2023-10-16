import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { ReactiveFormsModule } from '@angular/forms';
import {HttpClientModule} from '@angular/common/http';
import { PlanificationModule } from './planification/planification.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AuthentificationComponent } from './public/authentification/authentification.component';
import { PublicModule } from './public/public.module';
import { ProviderInterceptor } from './user.interceptor';
import { DialogContentComponent } from './dialog-content/dialog-content.component';
import { ToastrModule } from 'ngx-toastr';
import { FiltrePipe } from './pipe/filtre.pipe';


@NgModule({
  declarations: [
    AppComponent,
    AuthentificationComponent,
    DialogContentComponent,
    
  ],
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    PlanificationModule,
    PublicModule,
    ToastrModule.forRoot(),
    HttpClientModule, 
    AppRoutingModule, 
  ],
  providers: [ProviderInterceptor],
  bootstrap: [AppComponent]
})
export class AppModule { }
