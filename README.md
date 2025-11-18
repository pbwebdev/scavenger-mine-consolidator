# 🪙 Scavenger Mine Consolidator

A simple tool to help you **consolidate Scavenger Mine rewards** from one address (A) into another address (B).  
Built for the Midnight / Scavenger mining event to make the consolidation process easier for everyone.

Most people found the official `curl` method confusing — this tool lets you do everything **directly in your browser**, using your Cardano wallet.

Full documentation and details can be found on the Midnight post about this:
https://www.midnight.gd/news/how-to-consolidate-allocations-from-multiple-addresses-for-scavenger-mine

You can access the tool running at:
https://learncardano.io/consolidate.html

The code here is a reference and will allow you to audit and run it yourself.

---

## ⭐ What This Tool Does

When you mine Scavenger challenges, each mining address accumulates rights to NIGHT tokens.  
If you mined using multiple addresses or want everything to go into one main wallet, you must **assign** these rights from address A → address B.

The official method requires:

- curl  
- constructing a long API URL  
- encoding a signature manually  
- command line knowledge  

This tool replaces all of that with:

1. **Connect Cardano wallet** (Lace, Eternl, Typhon, Yoroi, Exodus, VESPR, Gero)  
2. **Select your mining address (A)**  
3. **Enter destination address (B)**  
4. Click **Sign & Consolidate**  
5. Your wallet signs the required message  
6. The tool forwards it to the official Scavenger API  
7. You get an immediate success/error response

Your private keys NEVER leave the wallet.

---

## ⚙️ Why a Backend Proxy Is Required

The Scavenger API does **not** allow direct browser requests due to CORS restrictions.

If the browser attempts:
https://scavenger.prod.gd.midnighttge.io/donate_to/
…it will fail with:

- “CORS blocked”
- “No Access-Control-Allow-Origin”
- “Failed to fetch”

Therefore, the tool needs a **tiny server-side proxy** to forward requests.

This proxy:

1. Receives `{ dest, orig, signature }` from the browser  
2. Sends it to the official Scavenger endpoint  
3. Returns the official JSON response  

### You can implement this proxy in:

- WordPress (included in this repo)
- Plain PHP  
- Node.js  
- Cloudflare Worker  
- Vercel/Netlify serverless function  
- Any backend that can issue POST requests

If you’re not using WordPress, simply copy the logic into your preferred backend.

---

## 🧩 How It Works (Step-by-Step)

### 1. User opens `consolidate.html`
A simple UI with wallet detection and instructions.

### 2. User connects wallet
Uses standard CIP-30 APIs to pull addresses and sign data.

### 3. The tool creates the required message to sign
Assign accumulated Scavenger rights to: {destination_address}


### 4. Wallet signs the message
The signature proves ownership of address A.

### 5. Browser sends the signature to your server
Your server forwards it to:
https://scavenger.prod.gd.midnighttge.io/donate_to/{B}/{A}/{signature}

### 6. Scavenger responds
For example:

- success message  
- number of solutions consolidated  
- errors (e.g. “address not registered”)  

### 7. The UI displays the result
Users see everything clearly in the browser, including a built-in console.

---

## 📦 Files in This Repo

### `consolidate.html`
Browser UI that handles:

- Wallet detection + icons  
- Destination (B) input  
- Wallet connection  
- Message signing  
- Proxy communication  
- Result display  
- Live console for debugging  
- ADAHandle & BuyMeACoffee donation links

### `scavenger-donate-proxy.php`
WordPress REST endpoint:

- Receives JSON from frontend  
- Forwards request to Scavenger  
- Returns JSON response  
- Avoids CORS issues completely  

If you're not using WordPress, you can convert this into a standalone PHP script or a serverless function easily.

---

## 🛠 Installation (WordPress)

1. Upload `scavenger-donate-proxy.php` to:
wp-content/mu-plugins/

(or to `wp-content/plugins/` and activate it)

2. Upload `consolidate.html` anywhere on your domain.

3. Open:
https://yourdomain.com/consolidate.html


Done.

---

## 🛠 Installation (Non-WordPress)

You only need a server endpoint that:

- accepts POST JSON
- forwards it to Scavenger
- returns JSON

Example implementations for:

- Node.js
- Cloudflare Worker
- PHP standalone
- Vercel/Netlify

…can be added to this repo if needed.

---

## 🔐 Security Notes

- No private keys are ever exposed.  
- The wallet signs **one text message only** (no transaction built).  
- The proxy does not store data.  
- The server simply forwards requests exactly as Scavenger requires.

This tool is fully safe when hosted on your own domain.

---

## 🙏 Support the Project

If this tool helped you consolidate your mining rewards:

### ☕ Buy Me a Coffee  
https://buymeacoffee.com/learncardano

### 🧡 Donate ADA via Handle  
**$RugMeDaddy**

Thanks for your support!

---

## 📬 Contact

If you have questions, issues, or feature requests:  
Open an issue here on GitHub or reach out on X: **@astroboysoup**





