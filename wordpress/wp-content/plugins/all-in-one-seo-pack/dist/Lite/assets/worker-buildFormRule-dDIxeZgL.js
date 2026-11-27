function o(n,t){if(t.includes(null))return n;for(const r of t)if(r.reg.test(n)===!0)return n.replace(r.reg,r.repl)}export{o as b};
