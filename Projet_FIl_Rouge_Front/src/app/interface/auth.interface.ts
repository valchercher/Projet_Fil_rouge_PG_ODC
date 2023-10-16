import { User } from "./all"

export interface Info{
    email:string
    password:string
}
export interface Utilisateur{
    status:number
    message:string
    token:string
    data:User
}