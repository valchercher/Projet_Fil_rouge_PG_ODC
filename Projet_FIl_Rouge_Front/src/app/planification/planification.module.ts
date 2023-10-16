import { Injectable, LOCALE_ID, NgModule } from '@angular/core';
import { CommonModule, registerLocaleData } from '@angular/common';
import { PlanificationSessionsComponent } from './planification-sessions/planification-sessions.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { ListerComponent } from './planification-cours/lister/lister.component';
import { AjouterComponent } from './planification-cours/ajouter/ajouter.component';
import { NavbarComponent } from '../navbar/navbar.component';
import { CalendarDateFormatter, CalendarModule, DateAdapter, DateFormatterParams } from 'angular-calendar';
import { adapterFactory } from 'angular-calendar/date-adapters/date-fns';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import localeFr from '@angular/common/locales/fr' 
import {MatPaginatorModule} from '@angular/material/paginator'
import { authGuard } from '../auth.guard';
import { InscriptionComponent } from './inscription/inscription.component';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { MatDialogModule } from '@angular/material/dialog';
import { ToastrModule } from 'ngx-toastr';
import { NotificationDemandeAnuulerComponent } from './notification-demande-anuuler/notification-demande-anuuler.component';
import { FiltrePipe } from '../pipe/filtre.pipe';
registerLocaleData(localeFr);
@Injectable()
class CustomDateFormatter extends CalendarDateFormatter{
  public override dayViewHour({ date, locale }: DateFormatterParams): string {
    return new Intl.DateTimeFormat(locale,{hour:'numeric',minute:'numeric'}).format(date)
  }
  public override weekViewHour({ date, locale }: DateFormatterParams): string {
    return new Intl.DateTimeFormat(locale,{hour:'numeric',minute:'numeric'}).format(date)
  }
}
@NgModule({
  declarations: [
    ListerComponent,
    AjouterComponent,
    NavbarComponent,
    PlanificationSessionsComponent,
    InscriptionComponent,
    FiltrePipe,
    NotificationDemandeAnuulerComponent,
  ],
  imports: [
    CommonModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    FormsModule,
    NgbModule,
    MatDialogModule,
    MatPaginatorModule,
    CalendarModule.forRoot({ provide: DateAdapter, useFactory: adapterFactory }),
    RouterModule.forChild([
      {
       path:'planifier/cours',component:AjouterComponent,
       canActivate:[authGuard],
       data:
       {
        role:["professeur","RP","attache"],
       },
      },
      {
        path:'lister/cours',component:ListerComponent,
        canActivate:[authGuard],
        data:
        {
         role:["professeur","RP","attache"],
        },
      },
      {
        path:'inscription/etudiant',component:InscriptionComponent,
        canActivate:[authGuard],
        data:
        {
         role:["professeur","RP","attache"],
        },
      },
      {
        path:'demandeAnnulation/Session/notidication',component:NotificationDemandeAnuulerComponent,
        canActivate:[authGuard],
        data:
        {
         role:["RP"],
        },
      },
    ])
  ],
  providers:[{provide:CalendarDateFormatter,useClass:CustomDateFormatter }
]
})
export class PlanificationModule { }
