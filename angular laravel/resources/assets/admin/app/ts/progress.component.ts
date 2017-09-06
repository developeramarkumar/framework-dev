import {Component} from '@angular/core';
import {MdProgressBarModule} from '@angular/material';
import { animation } from '@angular/animations';

@Component({
  selector: 'ng-app',
  templateUrl: '/admin/pages/progress.component.html?dir=html',
  styleUrls: ['/admin/pages/progress.component.css?dir=css'],
})
export class ProgressComponent {
  color = 'primary';
  mode = 'determinate';
  value = 50;
  bufferValue = 75;
}