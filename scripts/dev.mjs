import { spawn } from "node:child_process";
import { fileURLToPath } from "node:url";

const projectRoot = fileURLToPath(new URL("..", import.meta.url));
const isWindows = process.platform === "win32";
const children = [];

let stopped = false;

const processes = [
    ["server", ["artisan", "serve"]],
    ["queue", ["artisan", "queue:listen", "--tries=1", "--timeout=0"]],
    ...(!isWindows ? [["logs", ["artisan", "pail", "--timeout=0"]]] : []),
];

function forward(label, chunk, stream) {
    for (const line of chunk.toString().split(/(?<=\n)/)) {
        if (line === "") {
            continue;
        }

        stream.write(`[${label}] ${line}`);
    }
}

function stopChildren() {
    stopped = true;

    for (const child of children) {
        if (!child.killed) {
            child.kill("SIGTERM");
        }
    }
}

function start(label, args) {
    const child = spawn("php", args, {
        cwd: projectRoot,
        stdio: ["ignore", "pipe", "pipe"],
        windowsHide: true,
    });

    children.push(child);

    child.stdout.on("data", (chunk) => forward(label, chunk, process.stdout));
    child.stderr.on("data", (chunk) => forward(label, chunk, process.stderr));

    child.on("error", (error) => {
        if (stopped) {
            return;
        }

        process.stderr.write(`[${label}] ${error.message}\n`);
        stopChildren();
        process.exit(1);
    });

    child.on("exit", (code, signal) => {
        if (stopped || code === 0) {
            return;
        }

        const reason = signal ? `signal ${signal}` : `code ${code}`;

        process.stderr.write(`[${label}] exited with ${reason}\n`);
        stopChildren();
        process.exit(code ?? 1);
    });
}

process.on("SIGINT", () => {
    stopChildren();
    process.exit(0);
});

process.on("SIGTERM", () => {
    stopChildren();
    process.exit(0);
});

for (const [label, args] of processes) {
    start(label, args);
}

setInterval(() => {}, 1000);
