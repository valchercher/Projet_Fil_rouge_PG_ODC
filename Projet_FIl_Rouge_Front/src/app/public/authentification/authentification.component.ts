import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import {  Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { Response } from 'src/app/interface/all';
import { Utilisateur } from 'src/app/interface/auth.interface';
import { AuthService } from 'src/app/service/auth.service';

@Component({
  selector: 'app-authentification',
  templateUrl: './authentification.component.html',
  styleUrls: ['./authentification.component.css']
})
export class AuthentificationComponent implements OnInit
{
  loginForm:FormGroup;
  message:string=""
  isSubmit:boolean=true;
  
  constructor(private fb:FormBuilder,private serviceauth:AuthService,private route:Router,private atoastr:ToastrService)
  {
    this.loginForm=this.fb.group(
    {
      email:['',Validators.required],
      password:['',Validators.required]
    })
  }
  ngOnInit(): void
  {
   
  }
  get formControls()
  {
    return this.loginForm.controls;
  }
  seConnecter()
  {
    this.serviceauth.login(this.loginForm.value).subscribe(
        (response:Response<Utilisateur>) =>
        {
          if(response.status===200)
          {
            // let tex=new TextEncoder().encode(response.data.token);
            localStorage.setItem('token',btoa(response.data.token));
            localStorage.setItem('user',btoa((JSON.stringify(response.data.data))))
             let val=JSON.parse(atob(localStorage.getItem('user')?.toString()!));
             console.log(val);
            this.atoastr.success(response.message)
            this.route.navigateByUrl('/planifier/cours');
          }
        },(error) =>
        {
          console.error('Erreur : ', error.error.message);
          this.atoastr.error(error.error.message)   
        });
  }

}
