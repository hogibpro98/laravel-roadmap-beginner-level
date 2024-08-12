# Level 1/5. Beginner Level

## 🌀 Logos
- **laravel version:** <code>11</code><br/>
- **Bootstrap version:** <code>5</code><br/>
- **PHP:** <code>8.3</code><br/>
- **MySQL:** <code>8.4</code><br/>
- **Docker:** <code>https://github.com/hogibpro98/docker-compose-template</code><br/>

## 🚀 Quickstart
- Step 1: Clone <code>https://github.com/hogibpro98/docker-compose-template</code> 
- Step 2: Change .env to true config

## ⭐ Features
Goal: to create your very first simple Laravel project.

<table>
 <tr valign="top" style="vertical-align:top">
  <td>
   <strong>	Routing and Controllers: Basics</strong><br/>
<details><summary>⭐:<code>Callback Functions and Route::view()</code></summary>

1. What: cách đặt tên cho các đường dẫn
2. Where: <code>web.php</code> | <code>api.php</code>
3. How to: use method <code>name</code> Ex: Route::get('/home', [HomeController::class, 'index'])->name('home');
4. Why: ngắn gọn, rễ nhớ, thuận tiện sử dụng
5. When: Khi bạn biết rằng đường dẫn này sẽ được sử dụng ở nhiều nơi

<br/>
</details>
   ⭐: <code>Routing to a Single Controller Method</code><br/>
   ⭐: <code>Route Parameters</code><br/>
<details><summary>⭐:<code>Route Naming</code></summary>

1. What: cách đặt tên cho các đường dẫn
2. Where: <code>web.php</code> | <code>api.php</code>
3. How to: use method <code>name</code> Ex: Route::get('/home', [HomeController::class, 'index'])->name('home');
4. Why: ngắn gọn, rễ nhớ, thuận tiện sử dụng
5. When: Khi bạn biết rằng đường dẫn này sẽ được sử dụng ở nhiều nơi

</details>
   ⭐: <code>Route Groups</code><br/>
  </td>
  <td>
   <strong>Blade Basics</strong><br/>
   ⭐: <code>Displaying Variables in Blade</code><br/>
   ⭐: <code>Blade If-Else and Loop Structures</code><br/>
   ⭐: <code>Layout: @include, @extends, @section, @yield</code><br/>
   ⭐: <code>Blade Components</code><br/>
  </td>
  <td>
   <strong>Auth Basics</strong><br/>
   ⭐: <code>Starter Kits: Breeze (Tailwind) or Laravel UI (Bootstrap)</code><br/>
   ⭐: <code>Default Auth Model and Access its Fields from Anywhere</code><br/>
   ⭐: <code>Check Auth in Controller / Blade</code><br/>
   ⭐: <code>Auth Middleware</code><br/>
  </td>
  <td>
   <strong>Database Basics</strong><br/>
   ⭐: <code>Database Migrations</code><br/>
   ⭐: <code>Basic Eloquent Model and MVC: Controller -> Model -> View</code><br/>
   ⭐: <code>Eloquent Relationships: belongsTo / hasMany / belongsToMany</code><br/>
   ⭐: <code>Eager Loading and N+1 Query Problem</code><br/>
  </td>
 </tr>
 <tr valign="top" style="vertical-align:top">
  <td>
   <strong>Full Simple CRUD</strong><br/>
   ⭐: <code>Route Resource and Resourceful Controllers</code><br/>
   ⭐: <code>Forms, Validation and Form Requests</code><br/>
   ⭐: <code>File Uploads and Storage Folder Basics</code><br/>
   ⭐: <code>Table Pagination</code><br/>
  </td>
 </tr>
</table>

## 💡 Structure:

```mermaid
classDiagram
    note for Model "- Work with DB\n- User action log\n- Cache Data\n - Call job\n - Event"
    Controller <|-- Model
    View <|-- Controller
    
    class Controller {
        +Router call controller 
        +view()
        +json()
    }
    
    class Model {
        +Repository Pattern
        +interface(RepositoryContract.php)
        +abtract(BaseRepository.php)
        +create repository(Create a Repository class extend BaseRepository)
        +use in service(Create a service and use repository)
    }
    
    class View{
        -View data from controller
        -()
    }

```
