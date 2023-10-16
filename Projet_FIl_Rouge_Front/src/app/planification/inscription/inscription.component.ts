import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { ExcelUsers } from 'src/app/interface/all';
import { AllServiceService } from 'src/app/service/all-service.service';
import * as XLSX from 'xlsx';
@Component({
  selector: 'app-inscription',
  templateUrl: './inscription.component.html',
  styleUrls: ['./inscription.component.css']
})
export class InscriptionComponent implements OnInit
{
  inscription:FormGroup
  tableaux:any;
  importFile?: File;
  constructor(private fb:FormBuilder,private service:AllServiceService,private taoarts:ToastrService)
  {
    this.inscription=this.fb.group(
    {
      file:[],
    })
  }
  ngOnInit(): void 
  {
    
  }
  onchangeExcel(event:Event)
  {
    let excel=(event.target as HTMLInputElement).files;
    console.log(excel);
    if(excel)
    {
      const file=excel[0]; 
      this.importFile=file as File;
      let reader=new FileReader();
      reader.onload=(e:any)=>
      {
        let value=reader.result; 
        const jsonData=XLSX.read(value,{type:'binary'})  
        const sheetName = jsonData.SheetNames[0];       
        const worksheet = jsonData.Sheets[sheetName];   
        const Data = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
        this.tableaux=Data      
      }
      reader.readAsBinaryString(file);
    }
  }
  inscrireEtudiant()
  {
    console.log(this.importFile);
    const data:ExcelUsers={
      users:this.importFile?.name 
    }
    console.log(data);
    
    this.service.inscription(data).subscribe(
    (response)=>{
        if(response.status===200)
        {
          this.taoarts.success(response.message);
        }else{
          let mess:any=response
          this.taoarts.warning(mess.error);      
        }
      },error=>
      {
        this.taoarts.error(error.error.message)
      })      
  }
}
