<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Trunggmu Exam System</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body, html { height: 100%; width: 100%; overflow: hidden; font-family: 'Outfit', sans-serif; }

        body {
            display: flex; justify-content: center; align-items: center;
            background: linear-gradient(-45deg, #4facfe, #00f2fe, #6a11cb, #2575fc);
            background-size: 400% 400%;
            animation: gradientBG 12s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .app-container {
            width: 95%; max-width: 440px; height: 90vh; max-height: 820px;
            background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px); border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            display: flex; flex-direction: column; overflow: hidden; position: relative;
        }

        .screen { width: 100%; height: 100%; padding: 24px; display: none; flex-direction: column; overflow-y: auto; }
        .active { display: flex; }

        h1 { color: #fff; text-align: center; margin-bottom: 20px; font-size: 1.8rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }

        /* Custom Select Box */
        .select-box {
            background: rgba(255, 255, 255, 0.9); padding: 14px 18px; border-radius: 14px; 
            cursor: pointer; position: relative; font-weight: 600; margin-bottom: 15px;
            color: #333; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            display: flex; justify-content: space-between; align-items: center;
        }
        .select-box::after { content: '▼'; font-size: 0.8rem; color: #666; transition: transform 0.3s; }
        .select-box.open::after { transform: rotate(180deg); }
        
        .options-list {
            position: absolute; top: calc(100% + 8px); left: 0; right: 0; 
            background: #fff; border-radius: 14px; max-height: 0; overflow: hidden; 
            transition: max-height 0.3s ease, box-shadow 0.3s ease; z-index: 100;
        }
        .open .options-list { max-height: 220px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); overflow-y: auto; }
        .option-item { padding: 12px 18px; border-bottom: 1px solid #f0f0f0; transition: background 0.2s; color: #333; }
        .option-item:hover { background: #f8f9fa; color: #2575fc; }

        .input-time-box {
            background: rgba(255, 255, 255, 0.9); padding: 10px 18px; border-radius: 14px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 15px; font-weight: 600; color: #333;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .stepper-control { display: flex; align-items: center; gap: 6px; }
        .step-btn {
            width: 36px; height: 36px; background: #2575fc; color: #fff;
            border: none; border-radius: 10px; font-size: 1.2rem; font-weight: bold;
            cursor: pointer; display: flex; justify-content: center; align-items: center;
        }
        .input-time-box input {
            width: 55px; padding: 6px; border: 2px solid #e1e1e1;
            border-radius: 10px; font-size: 1.05rem; font-weight: 600; text-align: center; outline: none;
            background: #fff;
        }

        .btn {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white; border: none; padding: 14px;
            border-radius: 14px; cursor: pointer; font-weight: 600; font-size: 1rem;
            width: 100%; box-shadow: 0 4px 15px rgba(37, 117, 252, 0.35);
            transition: transform 0.2s, box-shadow 0.2s; text-align: center; display: inline-block;
        }
        .btn:active { transform: scale(0.98); }
        .btn-secondary { background: rgba(255, 255, 255, 0.3); color: #fff; margin-top: 10px; border: 1px solid rgba(255,255,255,0.5); }

        .bxh-card {
            flex: 1; background: rgba(0, 0, 0, 0.25); border-radius: 20px;
            padding: 16px; color: white; overflow-y: auto; margin-top: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .bxh-item {
            display: flex; justify-content: space-between; padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem;
        }

        /* Exam Screen */
        #exam-screen { background: #ffffff; color: #222; }
        .exam-header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .clock-badge { background: #111; color: #fff; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; }
        .paper-header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .paper-header h2 { font-size: 1.3rem; color: #333; }

        .q-item { margin-bottom: 24px; background: #f9fbfc; padding: 16px; border-radius: 14px; border: 1px solid #edf0f5; }
        .q-item p { font-weight: 600; margin-bottom: 12px; color: #2c3e50; line-height: 1.4; }
        
        .ans-opt { 
            display: flex; align-items: center; gap: 12px; padding: 10px 12px; 
            margin: 6px 0; cursor: pointer; border-radius: 10px; background: #fff;
            border: 1px solid #e2e8f0; color: #333;
        }
        .ans-opt:hover { background: #f1f5f9; border-color: #cbd5e1; }
        
        .circle { 
            width: 28px; height: 28px; border: 2px solid #cbd5e1; border-radius: 50%; 
            display: flex; justify-content: center; align-items: center; font-weight: 600; font-size: 0.85rem; color: #64748b;
        }
        .ans-opt.selected { border-color: #2575fc; background: #eff6ff; }
        .ans-opt.selected .circle { background: #2575fc; color: #fff; border-color: #2575fc; }

        /* Admin Screen */
        #admin-screen { background: #f8fafc; color: #1e293b; }
        .admin-form { background: #fff; padding: 16px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .admin-form input, .admin-form select, .admin-form textarea {
            width: 100%; padding: 10px 14px; margin: 8px 0 14px 0; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; outline: none; font-family: 'Outfit', sans-serif;
        }
        .admin-form label { font-weight: 600; font-size: 0.9rem; color: #475569; }
        .admin-q-card { background: #fff; padding: 14px; border-radius: 12px; margin-bottom: 10px; border: 1px solid #e2e8f0; }

        /* Tabs trong Admin */
        .admin-tabs { display: flex; gap: 10px; margin-bottom: 15px; }
        .tab-btn { flex: 1; padding: 10px; background: #e2e8f0; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; color: #475569; }
        .tab-btn.active-tab { background: #2575fc; color: white; }
        .tab-content { display: none; }
        .tab-content.active-content { display: block; }

        /* Modal */
        #name-modal {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.75); backdrop-filter: blur(5px);
            display: none; justify-content: center; align-items: center; z-index: 1000;
        }
        .modal-card { background: white; padding: 28px; border-radius: 24px; width: 88%; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .modal-card input { width: 100%; padding: 12px 16px; margin: 10px 0 20px 0; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; outline: none; }
    </style>
</head>
<body>

<div class="app-container">
    <!-- MÀN HÌNH CHÍNH -->
    <div id="setup-screen" class="screen active">
        <h1>©Trunggmu</h1>
        
        <div class="select-box" id="select-box">
            <span id="selected-text">-- Chọn đề thi --</span>
            <div class="options-list" id="menu-json"></div>
        </div>
        
        <div class="input-time-box">
            <span>Thời gian (phút):</span>
            <div class="stepper-control">
                <button type="button" class="step-btn" onclick="changeTime(-5)">-</button>
                <input type="number" id="exam-time-input" min="1" max="180" value="15">
                <button type="button" class="step-btn" onclick="changeTime(5)">+</button>
            </div>
        </div>

        <button class="btn" onclick="startExam()">VÀO THI NGAY</button>
        <button class="btn btn-secondary" onclick="openAdmin()">⚙️️ Quản lý câu hỏi</button>
        
        <div class="bxh-card">
            <h3 style="color: #ffd700; text-align: center; margin-bottom: 12px;">🏆 TOP 10 PRO PLAYERS</h3>
            <div id="lb-list">Đang tải BXH...</div>
        </div>
    </div>

    <!-- MÀN HÌNH LÀM BÀI -->
    <div id="exam-screen" class="screen">
        <div class="exam-header-bar">
            <div class="clock-badge">⏱️ <span id="clock">15:00</span></div>
            <button class="btn" style="width: auto; padding: 8px 18px; font-size: 0.85rem; background: #ef4444; box-shadow: none;" onclick="finishExam()">Nộp bài</button>
        </div>
        <div class="paper-header"><h2 id="subject-title">MÔN THI</h2></div>
        <div id="quiz-container"></div>
        <button class="btn" style="margin-top: 10px; margin-bottom: 20px;" onclick="finishExam()">NỘP BÀI THI</button>
    </div>

    <!-- MÀN HÌNH QUẢN LÝ CÂU HỎI (ADMIN) -->
    <div id="admin-screen" class="screen">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2 style="font-size: 1.3rem;">Quản Lý Đề Thi</h2>
            <button class="btn" style="width: auto; padding: 6px 14px; background: #64748b;" onclick="closeAdmin()">Quay lại</button>
        </div>

        <!-- Các tab chuyển đổi giữa Thêm thường và Thêm bằng JSON -->
        <div class="admin-tabs">
            <button class="tab-btn active-tab" onclick="switchTab('form')">Thêm Thủ Công</button>
            <button class="tab-btn" onclick="switchTab('json')">Thêm Bằng JSON</button>
        </div>

        <!-- TAB 1: THÊM THỦ CÔNG -->
        <div id="tab-form" class="tab-content active-content admin-form">
            <h3 style="margin-bottom: 10px; font-size: 1rem; color: #2575fc;">Thêm / Sửa Câu Hỏi</h3>
            <input type="hidden" id="edit-q-id">
            <label>Chuyên mục (Môn thi):</label>
            <input type="text" id="admin-category" placeholder="Ví dụ: toan, ly, hoa...">
            
            <label>Nội dung câu hỏi:</label>
            <textarea id="admin-question" rows="2" placeholder="Nhập câu hỏi..."></textarea>
            
            <label>Đáp án A:</label>
            <input type="text" id="admin-opt0" placeholder="Nội dung đáp án A">
            <label>Đáp án B:</label>
            <input type="text" id="admin-opt1" placeholder="Nội dung đáp án B">
            <label>Đáp án C:</label>
            <input type="text" id="admin-opt2" placeholder="Nội dung đáp án C">
            <label>Đáp án D:</label>
            <input type="text" id="admin-opt3" placeholder="Nội dung đáp án D">
            
            <label>Đáp án đúng (Chọn A, B, C hoặc D):</label>
            <select id="admin-answer">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>

            <button class="btn" onclick="saveQuestionToJSON()">LƯU CÂU HỎI</button>
        </div>

        <!-- TAB 2: THÊM BẰNG JSON -->
        <div id="tab-json" class="tab-content admin-form">
            <h3 style="margin-bottom: 10px; font-size: 1rem; color: #2575fc;">Nhập / Cập nhật toàn bộ Đề bằng JSON</h3>
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 10px;">Dán cấu trúc JSON vào ô dưới đây (hỗ trợ thêm nhiều câu cùng lúc hoặc ghi đè môn thi).</p>
            
            <label>Môn thi (Key):</label>
            <input type="text" id="json-category" placeholder="Ví dụ: toan, tienganh...">
            
            <label>Mảng câu hỏi (JSON Array):</label>
            <textarea id="json-raw-input" rows="8" placeholder='[
  {
    "question": "Câu hỏi số 1 là gì?",
    "options": ["Đáp án A", "Đáp án B", "Đáp án C", "Đáp án D"],
    "answer": "Đáp án A"
  }
]'></textarea>
            <button class="btn" onclick="saveByJSONRaw()">CẬP NHẬT TỪ JSON</button>
        </div>

        <h3 style="margin-bottom: 10px; font-size: 1rem;">Danh sách câu hỏi hiện tại:</h3>
        <div id="admin-quiz-list"></div>
    </div>

    <!-- MODAL NHẬP TÊN LƯU ĐIỂM -->
    <div id="name-modal">
        <div class="modal-card">
            <h3>HOÀN THÀNH XUẤT SẮC!</h3>
            <p>Kết quả của bạn đã được ghi nhận.</p>
            <input type="text" id="player-name" placeholder="Nhập tên của bạn..." maxlength="15">
            <button class="btn" id="save-btn" onclick="sendToData()">LƯU KẾT QUẢ</button>
        </div>
    </div>
</div>

<script>
    let fullData = {};
    let selectedKey = "";
    let userAns = {};
    let startTime, timer;
    let finalResult = {};
    let currentExamSeconds = 15 * 60;

    window.onload = () => {
        loadMenu();
        loadBXH();
    };

    function changeTime(amount) {
        const input = document.getElementById('exam-time-input');
        let currentVal = parseInt(input.value) || 15;
        let newVal = currentVal + amount;
        if (newVal < 1) newVal = 1;
        if (newVal > 180) newVal = 180;
        input.value = newVal;
    }

    async function loadMenu() {
        try {
            const res = await fetch('data.json?' + Date.now());
            fullData = await res.json();
            const menu = document.getElementById('menu-json');
            menu.innerHTML = "";
            Object.keys(fullData).forEach(key => {
                const div = document.createElement('div');
                div.className = 'option-item';
                div.textContent = key.toUpperCase();
                div.onclick = (e) => {
                    e.stopPropagation();
                    selectedKey = key;
                    document.getElementById('selected-text').textContent = key.toUpperCase();
                    document.getElementById('select-box').classList.remove('open');
                };
                menu.appendChild(div);
            });
        } catch (e) { console.error("Lỗi tải data.json"); }
    }

    document.getElementById('select-box').onclick = function() { this.classList.toggle('open'); };

    async function loadBXH() {
        const list = document.getElementById('lb-list');
        try {
            const res = await fetch('bxh.json?' + Date.now());
            if(!res.ok) throw new Error();
            const data = await res.json();
            list.innerHTML = data.map((p, i) => `
                <div class="bxh-item">
                    <span>#${i+1} ${p.name}</span>
                    <span><b>${p.score}đ</b> (${p.time})</span>
                </div>
            `).join('') || "Chưa có dữ liệu.";
        } catch (e) { list.innerHTML = "Chưa có dữ liệu BXH."; }
    }

    function startExam() {
        if (!selectedKey) return alert("Vui lòng chọn đề thi trước!");
        const timeInputVal = parseInt(document.getElementById('exam-time-input').value);
        const minutes = (!isNaN(timeInputVal) && timeInputVal > 0) ? timeInputVal : 15;
        currentExamSeconds = minutes * 60;

        document.getElementById('setup-screen').classList.remove('active');
        document.getElementById('exam-screen').classList.add('active');
        document.getElementById('subject-title').textContent = selectedKey.toUpperCase();
        
        startTime = Date.now();
        renderQuiz();
        startTimer();
    }

    function renderQuiz() {
        const container = document.getElementById('quiz-container');
        const qs = [...(fullData[selectedKey] || [])].sort(() => Math.random() - 0.5);
        container.innerHTML = qs.map((q, i) => `
            <div class="q-item">
                <p>Câu ${i+1}: ${q.question}</p>
                <div class="ans-group">
                    ${q.options.map((opt, oi) => `
                        <div class="ans-opt" id="q${i}o${oi}" onclick="select('${i}', '${oi}', '${opt.replace(/'/g, "\\'")}', '${q.answer.replace(/'/g, "\\'")}')">
                            <span class="circle">${String.fromCharCode(65+oi)}</span> 
                            <span>${opt}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    function select(qIdx, oIdx, val, correct) {
        document.querySelectorAll(`[id^="q${qIdx}o"]`).forEach(o => o.classList.remove('selected'));
        document.getElementById(`q${qIdx}o${oIdx}`).classList.add('selected');
        userAns[qIdx] = { choice: val, isCorrect: val === correct };
    }

    function startTimer() {
        let sec = currentExamSeconds;
        let initM = Math.floor(sec/60), initS = sec%60;
        document.getElementById('clock').textContent = `${initM}:${initS<10?'0':''}${initS}`;

        timer = setInterval(() => {
            sec--;
            let m = Math.floor(sec/60), s = sec%60;
            document.getElementById('clock').textContent = `${m}:${s<10?'0':''}${s}`;
            if(sec <= 0) finishExam();
        }, 1000);
    }

    async function finishExam() {
        clearInterval(timer);
        const total = (fullData[selectedKey] || []).length;
        const correct = Object.values(userAns).filter(a => a.isCorrect).length;
        const score = total > 0 ? ((correct / total) * 10).toFixed(2) : 0;
        const timeUsed = Math.floor((Date.now() - startTime) / 1000);

        finalResult = { 
            score: parseFloat(score), 
            rawTime: timeUsed, 
            time: Math.floor(timeUsed/60) + "m" + (timeUsed%60) + "s" 
        };

        document.getElementById('name-modal').style.display = 'flex';
    }

    async function sendToData() {
        const btn = document.getElementById('save-btn');
        const nameInput = document.getElementById('player-name');
        const name = nameInput.value.trim() || "Ẩn danh";
        
        btn.disabled = true;
        btn.innerText = "ĐANG LƯU...";

        try {
            const response = await fetch('save_score.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, ...finalResult })
            });

            const result = await response.json();
            if (result.status === "success") {
                alert("Đã lưu kết quả thành công!");
                location.reload();
            } else {
                alert("Lỗi: " + result.message);
                btn.disabled = false;
                btn.innerText = "LƯU KẾT QUẢ";
            }
        } catch (error) {
            alert("Lỗi kết nối Server!");
            btn.disabled = false;
            btn.innerText = "LƯU KẾT QUẢ";
        }
    }

    // --- ADMIN FUNCTIONS ---
    async function openAdmin() {
        document.getElementById('setup-screen').classList.remove('active');
        document.getElementById('admin-screen').classList.add('active');
        await loadMenu();
        renderAdminList();
    }

    function closeAdmin() {
        document.getElementById('admin-screen').classList.remove('active');
        document.getElementById('setup-screen').classList.add('active');
        loadMenu();
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active-tab'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active-content'));
        
        if(tabName === 'form') {
            document.querySelectorAll('.tab-btn')[0].classList.add('active-tab');
            document.getElementById('tab-form').classList.add('active-content');
        } else {
            document.querySelectorAll('.tab-btn')[1].classList.add('active-tab');
            document.getElementById('tab-json').classList.add('active-content');
        }
    }

    function renderAdminList() {
        const container = document.getElementById('admin-quiz-list');
        let html = "";
        Object.keys(fullData).forEach(cat => {
            html += `<h4 style="margin: 10px 0; color: #2575fc;">Môn: ${cat.toUpperCase()}</h4>`;
            fullData[cat].forEach((q, idx) => {
                html += `
                    <div class="admin-q-card">
                        <p><b>C${idx+1}:</b> ${q.question}</p>
                        <p style="font-size: 0.85rem; color: #64748b;">Đáp án đúng: <b>${q.answer}</b></p>
                        <div style="margin-top: 8px; display: flex; gap: 8px;">
                            <button class="btn" style="padding: 4px 10px; font-size: 0.8rem;" onclick="editQuestion('${cat}', ${idx})">Sửa</button>
                            <button class="btn" style="padding: 4px 10px; font-size: 0.8rem; background: #ef4444;" onclick="deleteQuestion('${cat}', ${idx})">Xóa</button>
                        </div>
                    </div>
                `;
            });
        });
        container.innerHTML = html || "<p>Chưa có câu hỏi nào.</p>";
    }

    function editQuestion(cat, idx) {
        switchTab('form');
        const q = fullData[cat][idx];
        document.getElementById('admin-category').value = cat;
        document.getElementById('edit-q-id').value = idx;
        document.getElementById('admin-question').value = q.question;
        document.getElementById('admin-opt0').value = q.options[0] || '';
        document.getElementById('admin-opt1').value = q.options[1] || '';
        document.getElementById('admin-opt2').value = q.options[2] || '';
        document.getElementById('admin-opt3').value = q.options[3] || '';
        
        const optIdx = q.options.indexOf(q.answer);
        if (optIdx !== -1) {
            document.getElementById('admin-answer').value = String.fromCharCode(65 + optIdx);
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function saveQuestionToJSON() {
        const category = document.getElementById('admin-category').value.trim().toLowerCase();
        const question_id = document.getElementById('edit-q-id').value;
        const question = document.getElementById('admin-question').value.trim();
        const opt0 = document.getElementById('admin-opt0').value.trim();
        const opt1 = document.getElementById('admin-opt1').value.trim();
        const opt2 = document.getElementById('admin-opt2').value.trim();
        const opt3 = document.getElementById('admin-opt3').value.trim();
        const answerChar = document.getElementById('admin-answer').value;

        if (!category || !question) return alert("Vui lòng nhập chuyên mục và nội dung câu hỏi!");

        // Lấy giá trị của đáp án đúng dựa vào lựa chọn A, B, C, D
        let answerVal = opt0;
        if(answerChar === 'B') answerVal = opt1;
        if(answerChar === 'C') answerVal = opt2;
        if(answerChar === 'D') answerVal = opt3;

        const formData = new URLSearchParams();
        formData.append('action', 'save_question');
        formData.append('category', category);
        formData.append('question_id', question_id);
        formData.append('question', question);
        formData.append('opt0', opt0);
        formData.append('opt1', opt1);
        formData.append('opt2', opt2);
        formData.append('opt3', opt3);
        formData.append('answer', answerVal);

        try {
            const res = await fetch('process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            });
            if (res.ok) {
                alert("Đã lưu câu hỏi thành công!");
                document.getElementById('edit-q-id').value = '';
                document.getElementById('admin-question').value = '';
                document.getElementById('admin-opt0').value = '';
                document.getElementById('admin-opt1').value = '';
                document.getElementById('admin-opt2').value = '';
                document.getElementById('admin-opt3').value = '';
                
                const fresh = await fetch('data.json?' + Date.now());
                fullData = await fresh.json();
                renderAdminList();
            } else {
                alert("Lỗi khi lưu!");
            }
        } catch (e) {
            alert("Lỗi kết nối Server!");
        }
    }

    // Hàm xử lý lưu bằng JSON trực tiếp
    async function saveByJSONRaw() {
        const category = document.getElementById('json-category').value.trim().toLowerCase();
        const rawJsonText = document.getElementById('json-raw-input').value.trim();

        if (!category) return alert("Vui lòng nhập tên chuyên mục (môn thi)!");
        
        let parsedQuestions;
        try {
            parsedQuestions = JSON.parse(rawJsonText);
            if (!Array.isArray(parsedQuestions)) throw new Error();
        } catch (e) {
            return alert("Định dạng JSON không hợp lệ! Hãy chắc chắn bạn nhập đúng mảng câu hỏi dạng [...]");
        }

        const formData = new URLSearchParams();
        formData.append('action', 'save_json_bulk');
        formData.append('category', category);
        formData.append('questions_json', JSON.stringify(parsedQuestions));

        try {
            const res = await fetch('process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            });
            if (res.ok) {
                alert("Đã cập nhật danh sách câu hỏi qua JSON thành công!");
                document.getElementById('json-raw-input').value = '';
                
                const fresh = await fetch('data.json?' + Date.now());
                fullData = await fresh.json();
                renderAdminList();
                switchTab('form');
            } else {
                alert("Lỗi khi lưu JSON!");
            }
        } catch (e) {
            alert("Lỗi kết nối Server!");
        }
    }

    async function deleteQuestion(cat, idx) {
        if (!confirm("Bạn có chắc chắn muốn xóa câu hỏi này?")) return;

        const formData = new URLSearchParams();
        formData.append('action', 'delete');
        formData.append('category', cat);
        formData.append('question_id', idx);

        try {
            const res = await fetch('process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            });
            if (res.ok) {
                const fresh = await fetch('data.json?' + Date.now());
                fullData = await fresh.json();
                renderAdminList();
            } else {
                alert("Lỗi khi xóa!");
            }
        } catch (e) {
            alert("Lỗi kết nối Server!");
        }
    }
</script>
</body>
</html>