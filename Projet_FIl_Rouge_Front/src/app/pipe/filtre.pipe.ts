import { Pipe, PipeTransform } from '@angular/core';
import { Cours } from '../interface/all';

@Pipe({
  name: 'filtre'
})
export class FiltrePipe implements PipeTransform {

  transform(value: Cours[], search:string,libelle:string): Cours[] {
   if(!search)
   {
    return value
   }
   if(!value)
   {
    return []
   }
    return value.filter((ele:any)=>{
      if(ele && search)
      {
        console.log(search);
        
        return ele.module_user.module[libelle].toLowerCase()===search.toLowerCase()
     }
     return false;
    })
  }
  
  }


