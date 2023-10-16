
import { isSameDay } from 'date-fns';
import { Component, OnInit, inject } from '@angular/core';
import { AbstractControl, AsyncValidatorFn, FormBuilder, FormGroup, ValidationErrors, Validators } from '@angular/forms';
import { PageEvent } from '@angular/material/paginator';
import { All, Annee, Classe, Cours, Etat, Module, RequestCours, Response, Salle, User, annee, etat, semestre } from 'src/app/interface/all';
import { AllServiceService } from 'src/app/service/all-service.service';
import { Observable, asyncScheduler, of } from 'rxjs';
import { Time } from '@angular/common';
import { SessionService } from 'src/app/service/session.service';
import { AllSession } from 'src/app/interface/session.interface';
import { Router } from '@angular/router';
import { getTypeLabel } from 'src/app/data.export';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-ajouter',
  templateUrl: './ajouter.component.html',
  styleUrls: ['./ajouter.component.css']
})
export class AjouterComponent implements OnInit
{
  cours:Cours[]=[];
  modules:Module[]=[];
  professeurs:User[]=[];
  professeurssss:User[]=[]; 
  ajouterCours:FormGroup;
  ajoutSession:FormGroup;
  classes:Classe[]=[];
  annee:Annee=annee;
  semestre:Annee=semestre
  page:number=0;
  page_current:number=6;
  total:number=0;
  coursExiste:Cours[]=[];
  message:string="";
  coursSession?:Cours;
  classeSesion:Cours[]=[];
  classess?:Cours;
  salles:Salle[]=[];
  capaciteSalles:Salle[]=[];
  presence:string="";
  cle: string="";
  search:string="";
  valSelecteds:Module[]|User[]|Classe[]|Etat[]=[]
  hidden:boolean=false;
  module?:Module;
  role:string="";
  anneeActuel?:Annee;
  recherche:string="";
  searchProfeseur:string=""
  constructor(
    private fb:FormBuilder,
    private service:AllServiceService,
    private sessionservice:SessionService,
    private router:Router,
    private toarstr:ToastrService
    )
  {
    this.ajouterCours=this.fb.group(
    {
      module_id:['',[Validators.required]],
      professeur_id:['',[Validators.required]],
      nb_heure_global:['',[Validators.required,Validators.min(15),Validators.max(50)]],
      classe_id:[,[Validators.required]]
    });
    this.ajoutSession=this.fb.group(
    {
      user_id:[],
      module_id:[],
      classe_id:["",],
      annee_id:[],
      semestre_id:[],
      date:['',Validators.required,this.ValidationDate],
      heure_debut:['',Validators.required,this.ValidatorsHeure],
      heure_fin:['',Validators.required,this.ValidatorHeureFin],
      salle_id:["",],
      background:[''],
      color:[''],
    })
  }
  ngOnInit(): void 
  {
    localStorage.setItem("Annee",JSON.stringify(this.annee))
    localStorage.setItem("semestre",JSON.stringify(this.semestre))
    let user=JSON.parse(atob(localStorage.getItem('user')?.toString()!));
    this.annee=JSON.parse(localStorage.getItem('Annee')?.toString()!)
    
    this.role=user.role
    this.cle=this.role
    this.search=`${user.id}`
    console.log(user); 
    this.getCours();
    this.load(this.role);
  }
  ValidatorsHeure: AsyncValidatorFn = (control: AbstractControl):
  | Promise<ValidationErrors | null>
  | Observable<ValidationErrors | null> => 
  {
    let heure_debut=control.value;
    let heure=this.convertirEnseconde(heure_debut); 
    if (!(28800 <= heure && heure <= 72000)) {
      return of({ error: "L'heure de début doit être comprise entre 8:00 et 20:00." });
    }
    return of(null);
  };
  ValidatorHeureFin:AsyncValidatorFn=(control:AbstractControl):
  |Promise<ValidationErrors | null>
  |Observable<ValidationErrors | null> =>
  {
    let h_f=control.value;
    let h_d=control.parent?.get('heure_debut')?.value;
    let heure_f=this.convertirEnseconde(h_f);
    let heure_d=this.convertirEnseconde(h_d);
    let dure=heure_f - heure_d;
    if(dure <3600){
      return of({error:"la durée du session est au moins une heure"})
    }
    if (!(32400 <= heure_f && heure_f <= 75600)) {
      return of({ error: "L'heure de début doit être comprise entre 8:00 et 21:00." });
    }
    if(heure_f<heure_d)
    {
      return of({error:"l'heure de fin ne doit pas inferieur a l'heure de debut"})
    }
    if(dure> 14400){
      return of({error:"La durée du session maximal est 4 heure"})
    }
    return of(null);
  }
  ValidationDate:AsyncValidatorFn =(control:AbstractControl):
  |Promise<ValidationErrors |null>
  |Observable<ValidationErrors | null> =>
  {
    let date=new Date(control.value );
    console.log(date);
    
    if(date.getDay()===6 ||date.getDay()===0)
    {
      return of({error:"pas de cours les Samedi et dimanche"})
    }
    if(date.getFullYear()!==2023)
    {
      return of({error:`L'annee doit etre ${this.annee.libelle}`});
    } 
    return of(null);
  }
  convertirEnseconde(heure:string)
  {
    let obj=heure.split(':');
    let minute= +obj[0] * 3600;
    let seconde= +obj[1] ;
    return minute + seconde;
  }
  validerExiste()
  {
    let forme=this.ajouterCours.value;
    let coursExists=this.cours.filter(el=>
      el.module_user.module.id==forme.module_id.id
      && el.module_user.user.id==forme.professeur_id.id
      && el.annee_scolaire_classe.classe.id==forme.classe_id.id
      )
      this.coursExiste=coursExists;
  }
  getUnifiedArray(): (Module | User | Classe | Etat)[]
  {
    return (this.valSelecteds as any) as (Module | User | Classe | Etat)[];
  }
  getTypeLabel(mod: Module | User | Classe | Etat | Salle, cle: string): string {
    return getTypeLabel(mod, cle);
  } 
  getCours()
  {
    this.service.index(this.page_current,this.page).subscribe(
    {
      next:(response=>
      {
        this.classes=response.data.classes
        this.modules=response.data.modules
        this.total=response.pagination.total;
        this.salles=response.data.salles;
        this.professeurssss=response.data.professeurs  
      })
    })
  }
  load(role:string)
  {
    this.service.load(role,this.page_current,this.cle,this.search,this.page).subscribe(
      {
        next:((respone:Response<All>)=>{
          this.cours=respone.data.cours
          console.log(respone);          
        })
      }
      )
  }
  onSelectedModule()
  {
    let module=this.ajouterCours.get('module_id')?.value;
    let modulesResult=this.modules.find(el=>el.libelle.toLowerCase().includes(module.libelle.toLowerCase()))!;
    this.professeurs=modulesResult?.professeur
  }
  onSubmit()
  {
    let annee=JSON.parse(localStorage.getItem("Annee")?.toString()!);
    let semestre=JSON.parse(localStorage.getItem("semestre")?.toString()!);
    console.log(this.ajouterCours.value);
    const data= this.ajouterCours.value;
    const request:RequestCours=
    {
      annee_id:annee.id,
      classe_id:data.classe_id.id,
      user_id:data.professeur_id.id,
      module_id:data.module_id.id,
      nb_heure_global:data.nb_heure_global,
      semestre_id:semestre.id,
    }
      this.service.storeCours(request).subscribe(
        (resp)=>
        {
          if(resp.status==200)
          {
            // this.cours.unshift(...resp.data.cours)
            this.toarstr.success(resp.message);
          }else{
            this.toarstr.warning(resp.message)
          }
        },error=>
        {
          this.toarstr.error(error.error.message);
        }) 
  }
  onChangePage(event:PageEvent)
  {
    this.page=event.pageIndex+1;
    this.page_current=event.pageSize;
    this.load(this.role);
  }
  onchangeSession(cour:Cours)
  {
    this.coursSession=cour;
    this.classeSesion=this.cours.filter(el=>el.module_user.user.id===cour.module_user.user.id);
  }
  onChargeModule(event:Event)
  {
    let selected=(event.target as HTMLSelectElement).value;
    let input=this.ajoutSession.get('classe_id')?.value
     this.classess=this.classeSesion.find(el=>el.annee_scolaire_classe.classe.id== input.classe.id)  
     this.capaciteSalles=this.salles.filter(ele=>ele.nombre>=input.classe.effectif);
  }
  AddSubmit()
  {
    let classe=this.ajoutSession.value;
    if(classe)
    {
      console.log(classe);
      this.ajoutSession.get('classe_id')?.setValue(classe.classe_id.classe.id);
      this.ajoutSession.get('annee_id')?.setValue(classe.classe_id.annee.id);
      this.ajoutSession.get('semestre_id')?.setValue(this.coursSession?.annee_scolaire_semestre.semestre.id)
      this.ajoutSession.get('user_id')?.setValue(this.coursSession?.module_user.user.id);
      this.ajoutSession.get('module_id')?.setValue(this.coursSession?.module_user.module.id)
     if(this.presence==='presentiel')
     {
      this.ajoutSession.get('salle_id')?.setValue(classe.salle_id.id);
     }else{
      this.ajoutSession.get('salle_id')?.setValue('');
     }
     console.log(this.ajoutSession.value);   
      this.sessionservice.storeSessions(this.ajoutSession.value).subscribe(
          (response:Response<AllSession>)=>
          {  
              console.log(response);
              if(response.status===200)
              {
                this.toarstr.success(response.message);
                this.router.navigateByUrl('/lister/cours'); 
              }else{
                this.toarstr.warning(response.message)
              }
          },error=>
          {
            console.log(error.error.message);   
            this.toarstr.error(error.error.message);
          })
    } 
  }
  toogleRadio(event:Event)
  {
    let radio=(event.target as HTMLInputElement).value;
    this.presence=radio;  
  }
  OnchoiceOpt(event:Event)
  {
    let input=(event.target as HTMLSelectElement)
    let opt=input.options[input.selectedIndex].text;
    console.log(input.value);
    
    this.search=opt
  }
  onSelectedOption(event:Event)
  {
    this.valSelecteds=[]
    let selected=(event.target as HTMLInputElement).value;
    this.cle=selected;
    if(selected=="module")
    {
      this.valSelecteds=this.modules;
    }
    else if(selected==="professeur")
    {
       this.valSelecteds=this.professeurssss;
       console.log(this.valSelecteds);
    }
    else if(selected==="etat")
    {
      this.valSelecteds=etat
    }
    else if(selected==="classe")
    {
      this.valSelecteds=this.classes
    }
  }
  onSearch()
  {
    this.load(this.role)   
  }
  onSearchProfesseur()
  {
    console.log(this.searchProfeseur);
    this.recherche=this.searchProfeseur
  }
}
