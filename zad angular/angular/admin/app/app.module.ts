import { NgModule }      from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule }    from '@angular/forms';
import { RouterModule }   from '@angular/router';
import { HttpModule } from '@angular/http';
import { animation } from '@angular/animations';
import {MdProgressBarModule} from '@angular/material';
import { AppComponent }  from './ts/app.component';
import { ProgressComponent }  from './ts/progress.component';
import { ClassComponent }  from './ts/class.component';
import { StudentComponent }  from './ts/student.component';
import { SectionComponent }  from './ts/section.component';
import { CenterComponent }  from './ts/center.component';
import { DashboardComponent }  from './ts/dashboard.component';
import { MenuComponent }  from './ts/menu.component';
;
@NgModule({
  	imports: [
    BrowserModule,
    MdProgressBarModule,
    FormsModule,
    HttpModule,
    RouterModule.forRoot([
        { path: 'admin/dashboard',  component: DashboardComponent },
        { path: 'admin/student',  component: StudentComponent },
      	{ path: 'admin/section',  component: SectionComponent },
      	{ path: 'admin/center',  component: CenterComponent },
      	{ path: 'admin/class', component: ClassComponent },
    ]),
  ],
  declarations: [
    MenuComponent,
    AppComponent,
    ProgressComponent,
    DashboardComponent,
    StudentComponent,  
    CenterComponent,
    ClassComponent,
    SectionComponent,
    
  ],
  bootstrap: [MenuComponent,AppComponent ]
})
export class AppModule { }