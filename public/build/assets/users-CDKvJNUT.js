document.addEventListener("DOMContentLoaded",function(){const t=document.querySelector(".professionals-search-container"),s=document.getElementById("professionalsSearchInput"),a=document.querySelector(".search-clear-btn"),l=document.querySelector(".search-submit-btn"),d=document.querySelector(".search-loading");document.querySelector(".search-results-info");const c=document.getElementById("professionalsGrid");let f;s&&c&&z(),H();function z(){s.addEventListener("input",F),s.addEventListener("keydown",D),a&&a.addEventListener("click",v),l&&l.addEventListener("click",p),g(),t.querySelector(".search-input-wrapper")||q()}function q(){const e=document.createElement("div");e.className="search-input-wrapper";const o=document.createElement("div");if(o.className="search-actions",!a){const n=document.createElement("button");n.type="button",n.className="search-clear-btn",n.setAttribute("aria-label","Clear search"),n.innerHTML=`
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `,n.addEventListener("click",v),o.appendChild(n)}if(!l){const n=document.createElement("button");n.type="button",n.className="search-submit-btn",n.setAttribute("aria-label","Search professionals"),n.innerHTML=`
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            `,n.addEventListener("click",p),o.appendChild(n)}if(!d){const n=document.createElement("div");n.className="search-loading",n.innerHTML='<div class="loading-spinner"></div>',o.appendChild(n)}s.parentNode.insertBefore(e,s),e.appendChild(s),e.appendChild(o)}function F(e){const o=e.target.value.trim();g(),f&&clearTimeout(f),f=setTimeout(()=>{o.length>=2?p(o):o.length===0&&(w(),y())},300)}function D(e){e.key==="Enter"?(e.preventDefault(),p()):e.key==="Escape"&&v()}function g(){const e=document.querySelector(".search-clear-btn");e&&(s.value.trim().length>0?e.classList.add("visible"):e.classList.remove("visible"))}function v(){s.value="",s.focus(),g(),w(),y(),s.style.transform="scale(0.98)",setTimeout(()=>{s.style.transform=""},150)}function p(e=null){const o=e||s.value.trim().toLowerCase();if(!o){w(),y();return}A(),setTimeout(()=>{const n=document.querySelectorAll(".profi-card");let i=0;n.forEach(r=>{(r.getAttribute("data-search")||"").includes(o)?(b(r),i++):I(r)}),j(),P(i,o),window.innerWidth<=768&&i>0&&c.scrollIntoView({behavior:"smooth",block:"start"})},200)}function w(){document.querySelectorAll(".profi-card").forEach(o=>b(o))}function b(e){e.style.display="",e.style.opacity="0",e.style.transform="translateY(20px)",requestAnimationFrame(()=>{e.style.transition="opacity 0.3s ease, transform 0.3s ease",e.style.opacity="1",e.style.transform="translateY(0)"})}function I(e){e.style.transition="opacity 0.2s ease, transform 0.2s ease",e.style.opacity="0",e.style.transform="translateY(-10px)",setTimeout(()=>{e.style.display="none"},200)}function A(){const e=document.querySelector(".search-loading"),o=document.querySelector(".search-submit-btn");e&&e.classList.add("visible"),o&&(o.style.opacity="0.6",o.style.pointerEvents="none")}function j(){const e=document.querySelector(".search-loading"),o=document.querySelector(".search-submit-btn");e&&e.classList.remove("visible"),o&&(o.style.opacity="",o.style.pointerEvents="")}function P(e,o){let n=document.querySelector(".search-results-info");n||(n=document.createElement("div"),n.className="search-results-info",c.parentNode.insertBefore(n,c));const i=e===1?"professional":"professionals";n.innerHTML=`
            Found <span class="results-count">${e}</span> ${i} 
            ${o?`matching "<strong>${o}</strong>"`:""}
            ${e===0?"- try adjusting your search terms":""}
        `,n.classList.add("visible")}function y(){const e=document.querySelector(".search-results-info");e&&e.classList.remove("visible")}function T(){const e=[{text:"Interior Design",icon:"home"},{text:"Architecture",icon:"building"},{text:"Construction",icon:"hammer"},{text:"Electrical",icon:"zap"},{text:"Plumbing",icon:"droplet"},{text:"Landscaping",icon:"tree"}];let o=document.querySelector(".search-suggestions");s.addEventListener("focus",()=>{!s.value.trim()&&!o&&V(e)}),document.addEventListener("click",n=>{t.contains(n.target)||k()})}function V(e){const o=document.createElement("div");o.className="search-suggestions",e.forEach(n=>{const i=document.createElement("div");i.className="suggestion-item",i.innerHTML=`
                <svg class="suggestion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="suggestion-text">${n.text}</span>
            `,i.addEventListener("click",()=>{s.value=n.text,p(n.text.toLowerCase()),k()}),o.appendChild(i)}),t.appendChild(o),requestAnimationFrame(()=>{o.classList.add("visible")})}function k(){const e=document.querySelector(".search-suggestions");e&&(e.classList.remove("visible"),setTimeout(()=>{e.remove()},300))}T();function $(){let e=-1;s.addEventListener("keydown",n=>{const i=document.querySelectorAll(".suggestion-item");if(i.length!==0)switch(n.key){case"ArrowDown":n.preventDefault(),e=Math.min(e+1,i.length-1),o(i);break;case"ArrowUp":n.preventDefault(),e=Math.max(e-1,-1),o(i);break;case"Enter":e>=0&&(n.preventDefault(),i[e].click());break;case"Escape":k(),e=-1;break}});function o(n){n.forEach((i,r)=>{r===e?(i.style.background="linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(255, 140, 95, 0.05))",i.style.color="var(--primary-color)"):(i.style.background="",i.style.color="")})}}$(),document.addEventListener("click",function(e){if(e.target.closest('a[href*="users.show"]'))return;e.target.closest(".profi-card")&&!e.target.closest("a")&&(e.preventDefault(),e.stopPropagation())});const C={clean:{icon:!1,iconHtml:"",buttonsStyling:!0,allowOutsideClick:!0,allowEscapeKey:!0,showClass:{popup:"animate__animated animate__fadeInDown animate__faster"},hideClass:{popup:"animate__animated animate__fadeOutUp animate__faster"}},confirmation:{showCancelButton:!0,reverseButtons:!0,focusCancel:!0,confirmButtonColor:"#dc3545",cancelButtonColor:"#6c757d"},success:{confirmButtonColor:"#28a745",timer:3e3,timerProgressBar:!0,showCancelButton:!1},error:{confirmButtonColor:"#dc3545",showCancelButton:!1},loading:{allowOutsideClick:!1,allowEscapeKey:!1,showConfirmButton:!1}};function m(e,o={}){const n={...C.clean},i=C[e]||{};return{...n,...i,...o}}function S(e){if(console.log("📢 Showing success message:",e),typeof Swal>"u"){alert(e);return}const o=m("success",{title:"Success!",text:e});Swal.fire(o)}function E(e){if(console.log("⚠️ Showing error message:",e),typeof Swal>"u"){alert(e);return}const o=m("error",{title:"Error",text:e});Swal.fire(o)}function L(e,o="Information"){if(console.log("ℹ️ Showing info message:",e),typeof Swal>"u"){alert(e);return}const n=m("clean",{title:o,text:e,confirmButtonColor:"#007bff",showCancelButton:!1});Swal.fire(n)}function B(e,o="Warning"){if(console.log("⚠️ Showing warning message:",e),typeof Swal>"u"){alert(e);return}const n=m("clean",{title:o,text:e,confirmButtonColor:"#ffc107",showCancelButton:!1});Swal.fire(n)}function H(){console.log("🔍 Checking for users session messages...");const e=document.querySelector("[data-session-success]");if(e){const r=e.getAttribute("data-session-success");r&&(console.log("✅ Found session success message:",r),S(r))}const o=document.querySelector("[data-session-error]");if(o){const r=o.getAttribute("data-session-error");r&&(console.log("❌ Found session error message:",r),E(r))}const n=document.querySelector("[data-session-info]");if(n){const r=n.getAttribute("data-session-info");r&&(console.log("ℹ️ Found session info message:",r),L(r))}const i=document.querySelector("[data-session-warning]");if(i){const r=i.getAttribute("data-session-warning");r&&(console.log("⚠️ Found session warning message:",r),B(r))}}window.showSuccess=S,window.showError=E,window.showInfo=L,window.showWarning=B,window.createModalConfig=m});let u=[],h=5,_=10*1024*1024,N=["application/pdf","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","image/jpeg","image/jpg","image/png","image/gif","image/webp"];document.addEventListener("DOMContentLoaded",function(){O(),R()});function O(){const t=document.getElementById("becomeProfessionalBtn");t&&t.addEventListener("click",function(){U()})}function R(){const t=document.getElementById("professionalsSearchInput"),s=document.getElementById("professionalsGrid");t&&s&&t.addEventListener("input",function(){const a=this.value.toLowerCase().trim();s.querySelectorAll(".profi-card").forEach(d=>{(d.getAttribute("data-search")||"").includes(a)?d.style.display="block":d.style.display="none"})})}function U(){const t=document.querySelector('meta[name="csrf-token"]').getAttribute("content");document.body.classList.add("modal-backdrop-blur"),Swal.fire({title:"Become a Professional",html:W(t),width:"700px",padding:"0",showCancelButton:!0,confirmButtonText:`
            <svg class="swal-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Send Request
        `,cancelButtonText:"Cancel",reverseButtons:!0,backdrop:!0,allowOutsideClick:!0,customClass:{popup:"swal2-popup-professional",title:"swal2-title-professional",confirmButton:"swal2-confirm-professional",cancelButton:"swal2-cancel-professional",htmlContainer:"swal2-html-professional"},didOpen:()=>{G()},willClose:()=>{document.body.classList.remove("modal-backdrop-blur")},preConfirm:()=>oe()}).then(s=>{s.isConfirmed&&ne(s.value)})}function W(t){return`
        <div class="professional-modal-header">
            <div class="modal-header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2>Become a Professional</h2>
            <p>Join our verified professionals and unlock exclusive features</p>
        </div>

        <form id="professionalRequestForm" enctype="multipart/form-data" class="professional-modal-form">
            <input type="hidden" name="_token" value="${t}">
            
            <div class="form-group">
                <label for="swal-specialization" class="form-label">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                    </svg>
                    Specialization *
                </label>
                <input type="text" name="specialization" id="swal-specialization" class="form-input" 
                       placeholder="e.g., Full Stack Developer, UI/UX Designer, Data Scientist" required>
                <small class="form-text">Describe your professional expertise and main area of specialization</small>
            </div>

            <div class="form-group">
                <label for="swal-files" class="form-label">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Professional Documents (Optional)
                </label>
                
                <div class="file-upload-container">
                    <div class="file-drop-zone" id="fileDropZone">
                        <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="file-upload-text">
                            <strong>Click to upload</strong> or drag and drop files here
                        </p>
                        <p class="file-upload-subtext">
                            PDF, Word documents, or images (Max: ${h} files, 10MB each)
                        </p>
                        <input type="file" id="swal-files" name="files[]" multiple 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp" 
                               class="file-input-hidden">
                    </div>
                    
                    <div class="selected-files-container" id="selectedFilesContainer" style="display: none;">
                        <h6 class="selected-files-title">Selected Files:</h6>
                        <div class="selected-files-list" id="selectedFilesList"></div>
                    </div>
                </div>
                
                <small class="form-text">
                    Upload certificates, diplomas, portfolio samples, or other proof of your professional qualifications
                </small>
            </div>

            <div class="professional-benefits">
                <h6 class="benefits-title">
                    <svg class="benefits-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Professional Benefits
                </h6>
                <ul class="benefits-list">
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verified professional badge
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Enhanced profile visibility
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Access to professional features
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Priority in search results
                    </li>
                </ul>
            </div>
        </form>
    `}function G(){const t=document.getElementById("swal-files"),s=document.getElementById("fileDropZone");u=[],t.addEventListener("change",K),s.addEventListener("click",()=>t.click()),s.addEventListener("dragover",Y),s.addEventListener("dragleave",X),s.addEventListener("drop",Z)}function K(t){const s=Array.from(t.target.files);M(s)}function Y(t){t.preventDefault(),t.stopPropagation(),t.currentTarget.classList.add("drag-over")}function X(t){t.preventDefault(),t.stopPropagation(),t.currentTarget.classList.remove("drag-over")}function Z(t){t.preventDefault(),t.stopPropagation(),t.currentTarget.classList.remove("drag-over");const s=Array.from(t.dataTransfer.files);M(s)}function M(t){const s=[],a=[];if(u.length+t.length>h){s.push(`Maximum ${h} files allowed. You can select ${h-u.length} more files.`);return}if(t.forEach(l=>{if(!N.includes(l.type)){s.push(`${l.name}: Invalid file type. Only PDF, Word documents, and images are allowed.`);return}if(l.size>_){s.push(`${l.name}: File size exceeds 10MB limit.`);return}if(u.some(d=>d.name===l.name&&d.size===l.size)){s.push(`${l.name}: File already selected.`);return}a.push(l)}),s.length>0){Swal.showValidationMessage(s.join("<br>"));return}u.push(...a),x()}function x(){const t=document.getElementById("selectedFilesContainer"),s=document.getElementById("selectedFilesList");if(u.length===0){t.style.display="none";return}t.style.display="block",s.innerHTML="",u.forEach((a,l)=>{const d=document.createElement("div");d.className="selected-file-item",d.innerHTML=`
            <div class="file-info">
                <svg class="file-icon ${Q(a.type)}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${ee(a.type)}
                </svg>
                <div class="file-details">
                    <span class="file-name">${a.name}</span>
                    <span class="file-size">${te(a.size)}</span>
                </div>
            </div>
            <button type="button" class="remove-file-btn" onclick="removeFile(${l})">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `,s.appendChild(d)})}function J(t){u.splice(t,1),x()}function Q(t){return t.includes("pdf")?"file-pdf":t.includes("word")||t.includes("document")?"file-word":t.includes("image")?"file-image":"file-generic"}function ee(t){return t.includes("pdf")||t.includes("word")||t.includes("document")?'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>':t.includes("image")?'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>':'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'}function te(t){if(t===0)return"0 Bytes";const s=1024,a=["Bytes","KB","MB","GB"],l=Math.floor(Math.log(t)/Math.log(s));return parseFloat((t/Math.pow(s,l)).toFixed(2))+" "+a[l]}function oe(){const t=document.getElementById("swal-specialization").value;return t.trim()?{specialization:t,files:u}:(Swal.showValidationMessage("Please enter your specialization"),!1)}function ne(t){const s=window.createModalConfig("loading",{title:"Submitting Request...",text:"Please wait while we process your professional request.",didOpen:()=>{Swal.showLoading()}});Swal.fire(s);const a=new FormData,l=document.querySelector('meta[name="csrf-token"]').getAttribute("content");a.append("_token",l),a.append("specialization",t.specialization),t.files.forEach((c,f)=>{a.append(`files[${f}]`,c)}),fetch("/profi-requests",{method:"POST",body:a,headers:{"X-Requested-With":"XMLHttpRequest"}}).then(c=>c.json()).then(c=>{if(c.success)window.showSuccess(c.message||"Your professional request has been submitted successfully and is pending review."),setTimeout(()=>{window.location.reload()},3e3);else throw new Error(c.message||"An error occurred")}).catch(c=>{console.error("Error:",c),window.showError(c.message||"Failed to submit your request. Please try again.")})}window.removeFile=J;
