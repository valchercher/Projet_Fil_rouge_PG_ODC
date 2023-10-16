import { Classe, Etat, Module, Salle, User } from "./interface/all";

export function getTypeLabel(mod: Module | User | Classe | Etat|Salle ,cle:string): string
{
  if ((mod as Module).libelle  && cle==="module")
  {
    return  (mod as Module).libelle; 
  }
  else if ((mod as User).nom  && cle==="professeur")
  {
    return (mod as User).prenom+" "+(mod as User).nom; 
  } 
  else if((mod as Classe) && cle==="classe")
  { 
    return (mod as Classe).libelle
  }
  else if((mod as Etat) && cle==="etat")
  {
    return (mod as Etat).libelle
  }
  else if((mod as Salle) && cle==="salle")
  {
    return (mod as Salle).nom
  }
  return '';
}
export const data=[
  {
    libelle:"valider"
  },
  {
    libelle:"annuler"
  },
  {
    libelle:"terminer"
  },
  {
    libelle:"en cours"
  }
]