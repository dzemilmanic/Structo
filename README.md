# 🏗️ Structo – Platform for Connecting Architecture & Construction Professionals with Clients

**Structo** is a full-stack web application built with Laravel (Blade) and MySQL. It serves as a bridge between everyday users and professionals in the architecture and construction industries. Users can request or offer services, post jobs, browse professionals, ask questions, and stay informed through industry news.

---

🌐 **Live Website:** [structo.app](https://structo.up.railway.app/)

---

## ⚙️ Tech Stack

- **Frontend:** Laravel Blade Templates  
- **Backend:** Laravel (PHP)  
- **Database:** MySQL  
- **Hosting:** Railway  

---

## 🔑 User Roles & Functionalities

### 👤 Guest (Unregistered User)
- Browse public content on the platform  
- View professional listings and service categories  
- Read industry news (via News API)  
- Register for a user account  

### 🙍‍♂️ Registered User (Standard User)
- Edit personal profile  
- Post a job and specify what kind of professional they need  
- View and request services offered by professionals  
- Post questions publicly for the community to answer  
- Submit testimonials about the platform  
- Apply to become a professional by uploading a CV and credentials  

### 👷 Professional User
- Create and manage service offerings  
- Apply for jobs posted by regular users  
- Answer questions on the platform  
- Submit testimonials  
- Manage own profile and credentials  

### 🛠️ Admin
- Approve or reject professional applications  
- Manage users (upgrade/downgrade roles, delete users)  
- Manage all posted jobs and services  
- Control all testimonials, questions, and answers  
- Manage service categories  
- Moderate platform activity and content  

---

## 📰 Features

- 🧰 Job Posting & Service Requesting  
- 🏗️ Professional Listings with Service Categories  
- 💬 Q&A Community Interaction  
- ✍️ Testimonials from Users and Professionals  
- 📰 News Feed Powered by Architecture & Construction News APIs  
- 🧾 Professional Application System (CV & Competency Proof Upload)  
- 👨‍💼 Admin Dashboard for Full System Control  

---

## 🔐 Data Access & Role-Based Permissions

- Only registered users can post jobs or services.  
- Only professionals can offer services or apply for jobs.  
- Admins can manage all users, content, and data.  
- Role upgrades (from user to professional) require admin approval.  
- Sensitive data and user management are secured through Laravel’s auth system and middleware.

---

## 🧪 Getting Started (Local Setup)

```bash
git clone https://github.com/dzemilmanic/structo.git
cd structo
composer install
cp .env.example .env
php artisan key:generate
# Set your DB credentials in the .env file
php artisan migrate --seed
php artisan serve
```

---

## 📄 License

This project is open-source and available under the MIT License.  
Feel free to use, modify, or contribute.

---

## 🤝 Contributing

If you want to contribute, feel free to fork the repository and submit a pull request. Feedback and improvements are always welcome!

---

## 📬 Contact

For any inquiries or suggestions:  
**Project Owner:** Džemil Manić  
**Email:** dzemilmanic@hotmail.com
