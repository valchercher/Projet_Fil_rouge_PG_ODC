import { Classe, Etat, Module, Response, Salle, User } from './../../../interface/all';
import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { CalendarEvent, CalendarView } from 'angular-calendar';
import { format, isSameDay, isSameMonth } from 'date-fns';
import { fr } from 'date-fns/locale';
import { AllSession } from 'src/app/interface/session.interface';
import { AllServiceService } from 'src/app/service/all-service.service';
import { EventColor } from 'calendar-utils';
import { Subject } from 'rxjs';
import {data, getTypeLabel} from'src/app/data.export'
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { MatDialog } from '@angular/material/dialog';
import { DialogContentComponent } from 'src/app/dialog-content/dialog-content.component';
@Component({
  selector: 'app-lister',
  templateUrl: './lister.component.html',
  styleUrls: ['./lister.component.css'],
  
})
export class ListerComponent implements OnInit
{
  
  viewDate:Date=new Date();
  locale: string = "fr";
  view:CalendarView=CalendarView.Week;
  CalendarView= CalendarView;
  events:CalendarEvent[]=[];
  activeDayIsOpen:boolean=false;
  refresh=new Subject<void>();
  valSelected:Module[]|User[]|Classe[]|Etat[]|Salle[]=[];
  cle:string="";
  libelle:string=""
  modules:Module[]=[]
  classes:Classe[]=[];
  professeurs:User[]=[];
  salless:Salle[]=[];
  etats:Etat[]=data;
  role:string="";
  red:string="red";
  green:string="green";
  blue:string='blue';
  isModalVisible:boolean=true;
  @ViewChild('form') formElement?:ElementRef; 
  @ViewChild('sessionModal') sessionModal!: ElementRef<any>;
  constructor(private serviceSession:AllServiceService ,private modal:NgbModal,private dialog:MatDialog)
  {

  
}
ngOnInit(): void
{
  let user=JSON.parse(atob(localStorage.getItem('user')?.toString()!));
  this.role=user.role;
  this.cle=user.role;
  this.libelle=user.id
  this.getindexSessions(this.role)
  this.getInfo();
  // if(this.events.length>0){
  // }
  }
  setView(view:CalendarView)
  {
    this.view=view;
  }
  dayClicked({date,events}:{date:Date;events:CalendarEvent[]}):void
  {
    if(isSameMonth(date,this.viewDate))
    {
      if((isSameDay(this.viewDate,date) && this.activeDayIsOpen ===true)||events.length===0)
      {
        this.activeDayIsOpen=false;
      }else
      {
        this.activeDayIsOpen=true;
      }
      this.viewDate=date;
    }
  }
  eventClicked(event:any)
  { 
     const event1=event.event.title.split('<br>');
     const date=event.event.start.toString().slice(0,16);
     const event2=event.event.start.toString().slice(16,21);   
     const  event3=event.event.end.toString().slice(16,21); 
    const dialogRef = this.dialog.open(DialogContentComponent,
    {
      width:'650px',
      data: { title: event1,etat:event.event.etat, start:event2,date:date ,end:event3 ,id:event.event.id},
    });
    dialogRef.afterClosed().subscribe(result => {
      // console.log('Dialogue fermé avec résultat:', result);
    });
  }
  getUnifiedArray()
  {
    return (this.valSelected as any) as (Module|User|Classe|Etat|Salle)[]
  }
  eventTimesChanged(event:any)
  {
    event.event.start=event.newStart;
    event.event.end=event.newEnd;
    this.refresh.next()
  }
  getTypeLabel(mod:Module|User|Classe|Etat|Salle,cle:string):string
  {
    return getTypeLabel(mod,cle)
  }
  getInfo()
  {
    this.serviceSession.index(2,1).subscribe(
      {
        next:(response)=>{  
         this.professeurs=response.data.professeurs
          this.modules=response.data.modules;
          this.classes=response.data.classes;  
          this.salless=response.data.salles;        
        }
      }
    )
  }
  getindexSessions(role:string)
  {
    this.serviceSession.indexsession(role,this.cle,this.libelle).subscribe(
      {
      next:((response:Response<AllSession>)=>
      {
        console.log(response);
      response.data.sessions.forEach(ele=>
        {
        let debut=ele.heure_debut.split(':'); 
        let message="";
        let status=ele?.salle?.id;
        if(ele)
        if(isNaN(status)){
          message="En ligne";
        }else{
          message="presentiel"
        }
        const  colors: Record<string, EventColor> ={
          red:{
            primary:ele.color,
            secondary:ele.background,
          }
        }
        const event=
        {
          id:ele.id,
          title:  ` module: ${ele.cours.module_user.module.libelle} <br>
                    prof: ${ele?.cours?.module_user?.user.prenom+" "+ele?.cours?.module_user?.user?.nom} <br>
                    salle: ${ele?.salle?.nom}<br>
                    cours:  ${message} <br>
                    classe: ${ele?.cours?.annee_scolaire_classe?.classe?.libelle} <br>`,
          start:new Date(ele?.date+"T"+ele?.heure_debut),
          end:new Date(ele?.date+"T"+ele?.heure_fin),
          etat:ele.valider_session,
          actions:[
            {
              label: '<i class="fas fa-fw fa-pencil-alt"></i>',
              onClick: ({ event }: { event: CalendarEvent }): void => {
                console.log('Edit event', event);
              },
            }
          ],
          color:{...colors['red']},
          draggable:true,
          resizable:{
            beforeStart:true,
            afterEnd:true
          },
           cssClass:`color:${ele.color}`
        }
        this.events.push(event);
      })
      })
    })
  }
  
  onSelectedOption(event:Event)
  {
    let select=event.target as HTMLSelectElement;
    let value=select.options[select.selectedIndex].text;
      this.cle=value;
      this.valSelected=[]
      console.log(value);
    if(value==="professeur")
    {
      console.log(this.professeurs);
      this.valSelected=this.professeurs;
    }
    else if(value=="module")
    {
      this.valSelected=this.modules
    }
    else if(value==="salle")
    {
      console.log(this.salless);
      
      this.valSelected=this.salless
    }
    else if(value=="classe")
    {
      this.valSelected=this.classes
    }
    else if(value=="etat")
    {
      this.valSelected=this.etats
    }
  }
  OnchoiceOpt(event:Event)
  {
    let opt=event.target as HTMLSelectElement;
    let value=opt.options[opt.selectedIndex].text;
    console.log(value);
    this.libelle=value; 
  }
  onSearch()
  {
    this.getindexSessions(this.role);
  }
  
}
