import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NotificationDemandeAnuulerComponent } from './notification-demande-anuuler.component';

describe('NotificationDemandeAnuulerComponent', () => {
  let component: NotificationDemandeAnuulerComponent;
  let fixture: ComponentFixture<NotificationDemandeAnuulerComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [NotificationDemandeAnuulerComponent]
    });
    fixture = TestBed.createComponent(NotificationDemandeAnuulerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
