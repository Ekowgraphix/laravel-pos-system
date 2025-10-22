# 🚀 QUICK START - Background Selector

## ⚡ **3 Simple Steps:**

### **STEP 1: Save Images** 📁
Save your 5 images to: `public/auth-backgrounds/`

**Rename them to:**
- Image 1 (blue orbiting card) → **bg1.jpg**
- Image 2 (POS terminal) → **bg2.jpg**
- Image 3 (glowing card) → **bg3.jpg**
- Image 4 (phone with cards) → **bg4.jpg**
- Image 5 (purple hand) → **bg5.jpg**

### **STEP 2: Clear Cache** 🔄
```bash
php artisan cache:clear
php artisan view:clear
```

### **STEP 3: Test It!** ✨
1. Go to: `http://localhost/login`
2. See purple palette button (bottom-right)
3. Click it
4. Choose a background!

---

## 🎯 **That's It!**

Your login and signup pages now have:
- ✅ Floating background selector
- ✅ 6 beautiful options
- ✅ Smooth transitions
- ✅ Remembers your choice
- ✅ Works on mobile

**Enjoy your upgraded authentication pages!** 🎨✨

---

## 📂 **Folder Structure:**
```
public/
└── auth-backgrounds/
    ├── bg1.jpg  ← Blue orbiting card
    ├── bg2.jpg  ← POS terminal
    ├── bg3.jpg  ← Glowing card
    ├── bg4.jpg  ← Phone with cards
    └── bg5.jpg  ← Purple hand with card
```

## 🎨 **Visual:**
```
Login/Signup Page
┌─────────────────────────────────────┐
│                                     │
│  [Beautiful Background Image]       │
│                                     │
│      ┌─────────────────┐            │
│      │  Login Form     │            │
│      │  (Glassmorphism)│            │
│      └─────────────────┘            │
│                                     │
│                          [🎨]←Button│
│                           ↑         │
└───────────────────────────┼─────────┘
                            │
                    Click to change!
```

---

**For detailed info, see: `BACKGROUND_SELECTOR_GUIDE.md`**
