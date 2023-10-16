import { Component, OnInit } from '@angular/core';
import { AllServiceService } from '../service/all-service.service';
import { AuthService } from '../service/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {
role:string="";
constructor(private service:AuthService,private router:Router){}
  ngOnInit(): void {
    let user=JSON.parse(atob(localStorage.getItem('user')?.toString()!));
    this.role=user.role;
    
  }
  estDeconnecter()
  {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.service.logout().subscribe({
      next:(response)=>{
        console.log(response); 
        this.router.navigateByUrl('/login')   
      }
    })
  }
}
