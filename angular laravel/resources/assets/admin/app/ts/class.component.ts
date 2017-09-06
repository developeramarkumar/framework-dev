import { Component } from '@angular/core';
import { Http } from '@angular/http';
@Component({
 	selector: 'my-app',
 	templateUrl: '/admin/pages/class.component.html?dir=html&dir2=class',
})
export class ClassComponent { 
// Inject HttpClient into your component or service.
  constructor(private http: Http) {}
  
  ngOnInit(){
  	this.http.get('/admin/class/list').subscribe(data => {
      // Read the result field from the JSON response.
      console.log(data);
    });
  }
 }
