# OpenEf Framework

OpenEf Framework 是一个基于 PHP 的轻量级框架，专注于命令行工具（CLI）开发，集成了事件调度、配置管理等核心功能，旨在简化 PHP 命令行应用的开发流程。


## 特性

- **命令行工具支持**：基于 `symfony/console` 构建，支持通过注解或代码定义命令、参数及选项，提供丰富的交互能力（输入提示、进度条、表格输出等）。
- **事件驱动架构**：通过 `symfony/event-dispatcher` 实现事件机制，支持注解注册监听器，轻松扩展命令执行的生命周期逻辑。
- **灵活的配置管理**：支持 `.env` 环境变量和 PHP 配置文件（含自动加载目录），配置合并逻辑满足多环境需求。
- **依赖注入**：基于容器管理依赖，支持注解扫描和配置注册服务，降低组件耦合度。
- **规范的开发工具**：集成代码格式化（`php-cs-fixer`）、静态分析（`phpstan`）和单元测试（`phpunit`），确保代码质量。


## 环境要求

- PHP >= 8.2
- Composer


## 安装

通过 Composer 安装：

```bash
composer require open-ef/framework
```


## 快速开始

### 1. 创建一个命令

通过注解定义一个简单的命令：

```php
<?php
declare(strict_types=1);

namespace App\Command;

use OpenEf\Framework\Command\Command;
use OpenEf\Framework\Command\Annotation\Command as CommandAnnotation;

#[CommandAnnotation(
    name: 'greet',
    description: 'Say hello to someone',
    arguments: [
        ['name', InputArgument::REQUIRED, 'The name to greet']
    ]
)]
class GreetCommand extends Command
{
    public function handle()
    {
        $name = $this->argument('name');
        $this->info("Hello, {$name}!");
    }
}
```


### 2. 注册命令

命令会通过注解自动扫描注册，无需额外配置。若需手动注册，可在 `config/commands.php` 中添加：

```php
return [
    App\Command\GreetCommand::class,
];
```


### 3. 运行命令

```bash
php bin/console greet World
# 输出：Hello, World!
```


## 核心功能

### 命令行交互

框架提供丰富的 IO 交互方法，例如：

```php
// 提示用户输入
$name = $this->ask('What is your name?');

// 确认操作
if ($this->confirm('Do you want to continue?')) {
    // 执行逻辑
}

// 显示表格
$this->table(
    ['Name', 'Email'],
    [
        ['John Doe', 'john@example.com'],
        ['Jane Doe', 'jane@example.com'],
    ]
);
```


### 事件监听

通过事件监听器扩展命令逻辑，例如监听命令执行前事件：

```php
<?php
declare(strict_types=1);

namespace App\Listener;

use OpenEf\Framework\Command\Event\BeforeHandle;
use OpenEf\Framework\EventDispatcher\Annotation\Listener;
use OpenEf\Framework\EventDispatcher\ListenerInterface;

#[Listener(priority: 10)]
class LogCommandStartListener implements ListenerInterface
{
    public function listen(): array
    {
        return [BeforeHandle::class];
    }

    public function process(object $event): void
    {
        $command = $event->getCommand();
        // 记录命令开始执行的日志
        error_log("Command {$command->getName()} started.");
    }
}
```


### 配置管理

1. **环境变量**：在项目根目录创建 `.env` 文件：

```env
APP_ENV=dev
DEBUG=true
```

通过 `env()` 函数获取：

```php
$env = env('APP_ENV', 'prod');
$debug = env('DEBUG', false);
```

2. **配置文件**：在 `config/` 目录下创建配置文件（如 `app.php`）：

```php
return [
    'name' => 'My Application',
];
```

通过配置容器获取：

```php
$config = $container->get(ConfigInterface::class);
$appName = $config->get('app.name');
```


## 测试

运行单元测试：

```bash
composer test
```

代码静态分析：

```bash
composer analyse
```

代码格式化：

```bash
composer cs-fix
```


## 许可证

本项目基于 MIT 许可证开源，详见 [LICENSE](LICENSE) 文件。