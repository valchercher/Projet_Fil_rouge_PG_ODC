import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { Sessions } from 'src/app/interface/session.interface';
import { SessionService } from 'src/app/service/session.service';

@Component({
  selector: 'app-notification-demande-anuuler',
  templateUrl: './notification-demande-anuuler.component.html',
  styleUrls: ['./notification-demande-anuuler.component.css']
})
export class NotificationDemandeAnuulerComponent implements OnInit
{
  constructor(private sessionservice:SessionService,private toart:ToastrService)
  {}
  data:Sessions[]=[]
  motif:string="";
  idDemande:number=0;
  ngOnInit(): void
  {
    this.getDemandeAnnulationSession()
  }
  getDemandeAnnulationSession()
  {
    this.sessionservice.getDemandeAnnulation().subscribe(
      {
        next:(response)=>{
          console.log(response);
          this.data=response.data.sessions
        }
      }
    )
  }
  voirMotif(cour:Sessions)
  {
    this.motif=cour.motif;
    this.idDemande=cour.id;
  }
  demandeAccepter()
  {
    this.sessionservice.annulerSession(this.idDemande).subscribe(
      (response)=>
      {
        if(response.status===200)
        {
          this.toart.success(response.message);
        }else{
          this.toart.warning(response.message);
        }
      },error=>
      {
        this.toart.error(error.error.message)
      }
    )
  }
  annulerDemande()
  {
    this.sessionservice.annulerDemande(this.idDemande).subscribe(
      (response)=>
      {
        if(response.status===200)
        {
          this.toart.success(response.message);
        }else{
          this.toart.warning(response.message);
        }
      },error=>
      {
        this.toart.error(error.error.message)
      }
    )
  }

  
}
