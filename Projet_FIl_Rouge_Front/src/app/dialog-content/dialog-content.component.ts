import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { SessionService } from '../service/session.service';
import {  ToastrService } from 'ngx-toastr';
import { demandeAnuuler } from '../interface/session.interface';

@Component({
  selector: 'app-dialog-content',
  templateUrl: './dialog-content.component.html',
  styleUrls: ['./dialog-content.component.css']
})
export class DialogContentComponent implements OnInit 
{
  role:string=""
  isopen:boolean=false;
  maxCharacters:number=200;
  motif:FormGroup
  constructor(@Inject(MAT_DIALOG_DATA) public data: any,
      public dialogRef: MatDialogRef<DialogContentComponent>,
      private fb:FormBuilder,
      private serviceSession:SessionService,
      private toarst:ToastrService)
  {
    this.motif=this.fb.group({
      message:[''],
    })
  }
  get messages()
  {
    return this.motif.get('message')?.value;
  }

  ngOnInit(): void {
    let user=JSON.parse(atob(localStorage.getItem('user')?.toString()!));
    this.role=user.role;
  }
  onCloseClick(): void {
    this.dialogRef.close();
  }
  onDemandeClick()
  {
    this.isopen=true;
    // this.dialogRef.close();
  }
  envoieDemande(id:number)
  {
    const data:demandeAnuuler={
      message:this.messages
    }
    this.serviceSession.demandeAnnulerSession(id,data).subscribe(
      (response)=>{
        if(response.status==200)
        {
          this.toarst.success(response.message)
        }else{
          this.toarst.warning(response.message)
        }
      },error=>
      {
        this.toarst.error(error.error.message)
      })
    this.dialogRef.close();
  }
  validerSession(id:number)
  {
    console.log(id);
    
    this.serviceSession.validerSession(id).subscribe(
      (response)=>{
        if(response.status===200)
        {
          this.toarst.success(response.message)
        }else{
          this.toarst.warning(response.message)
        }
      },error=>
      {
        this.toarst.error(error.error.message)
      })
      setTimeout(()=>{
        this.dialogRef.close();
      },5000)
  }
  inValiderSession(id:number)
  {
    console.log(id);
    
    this.serviceSession.annulerSession(id).subscribe(
      (response)=>
      {
        if(response.status===200)
        {
          this.toarst.success(response.message);
        }else{
          this.toarst.warning(response.message);
        }
      },error=>
      {
        this.toarst.error(error.error.message)
      }
    )
    setTimeout(()=>{
      this.dialogRef.close();
    },5000)
  }
}
