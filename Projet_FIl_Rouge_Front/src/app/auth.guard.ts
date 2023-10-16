import { inject } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivateFn, Router, RouterStateSnapshot } from '@angular/router';
import { AuthService } from './service/auth.service';

export const authGuard: CanActivateFn = (
  route:ActivatedRouteSnapshot,
  state:RouterStateSnapshot
) =>
{
  const router=inject(Router);
  const service=inject(AuthService);
  if(service.estConnecter())
  {
    const isAuthorized = route.data['role'].some((authorize: string) =>
    service.user?.role.includes(authorize));
    if(isAuthorized)
    {
      return true;
    }
    else
    {
      window.alert("vous n'avez pas l'authorisation");
      return router.createUrlTree(['/unauthorized']); 
    }
  }
  router.createUrlTree(['/login']);
  return false;
};
