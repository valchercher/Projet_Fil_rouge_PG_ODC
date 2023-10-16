import { Cours, Salle } from "./all"

export interface Sessions{
    id: number,
    date: string,
    heure_debut: string,
    heure_fin: string,
    valider_session: boolean,
    demande_annuler: boolean,
    motif:string
    background:string
    color:string
    cours:Cours
    salle:Salle
}
export interface AllSession{
    sessions:Sessions[],
}
export interface RequestSession{
    user_id:number
    classe_id:number
    module_id:number
    salle_id:number|null
    annee_id:number
    semestre_id:number
    date:string
    heure_debut:string
    heure_fin:string
}
export interface demandeAnuuler{
    message:string
}