<?php

declare(strict_types=1);

namespace AwdEs\Attribute\Reflection\EventHandler;

use AwdEs\Attribute\EventHandler;
use AwdEs\Attribute\Reflection\EventHandler\Exception\WrongEventHandlerDeclaration;
use AwdEs\Event\Handling\EventHandlerMethodResolver;

final readonly class AttributeEventHandlerMethodResolver implements EventHandlerMethodResolver
{
    #[\Override]
    public function find(string $eventConsumer): \Generator
    {
        $reflection = new \ReflectionClass($eventConsumer);

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(EventHandler::class);

            if (0 === \count($attributes)) {
                continue;
            }

            foreach ($attributes as $attribute) {
                /** @var EventHandler $attrInstance */
                $attrInstance = $attribute->newInstance();
                $eventType = $attrInstance->eventType;

                $this->validateHandlerMethod($eventConsumer, $method, $eventType);

                yield $method->getName() => $eventType;
            }
        }
    }

    /**
     * @param class-string                           $class
     * @param class-string<\AwdEs\Event\EntityEvent> $eventType
     *
     * @throws WrongEventHandlerDeclaration
     */
    private function validateHandlerMethod(string $class, \ReflectionMethod $method, string $eventType): void
    {
        if (false === $method->isPublic()) {
            throw new WrongEventHandlerDeclaration(\sprintf('An event-handler method "%s::%s()" must be public.', $class, $method->getName()));
        }

        $countOvMethodParameters = \count($method->getParameters());
        if (1 !== $countOvMethodParameters) {
            throw new WrongEventHandlerDeclaration(\sprintf('An event-handler method "%s::%s()" must accept exactly one parameter, %d provided.', $class, $method->getName(), $countOvMethodParameters));
        }

        $param = $method->getParameters()[0];

        if (true === $param->isVariadic()) {
            throw new WrongEventHandlerDeclaration(\sprintf('An event-handler method "%s::%s()" must not be a variadic.', $class, $method->getName()));
        }

        $paramType = $param->getType();

        if (null === $paramType) {
            throw new WrongEventHandlerDeclaration(\sprintf('An event-handler method\'s "%s::%s()" parameter must have an implicit type.', $class, $method->getName()));
        }

        if ($paramType instanceof \ReflectionIntersectionType) {
            throw new WrongEventHandlerDeclaration(\sprintf('An event-handler method\'s "%s::%s()" must not have union types.', $class, $method->getName()));
        }

        if ($paramType instanceof \ReflectionUnionType) {
            foreach ($paramType->getTypes() as $type) {
                if (!$type instanceof \ReflectionNamedType) {
                    continue;
                }

                if ($type->getName() === $eventType) {
                    return;
                }
            }

            throw new WrongEventHandlerDeclaration(\sprintf('The type of an event handler "%s::%s()" must include the event "%s" as the type.', $class, $method->getName(), $eventType));
        }

        if (!$paramType instanceof \ReflectionNamedType) {
            throw new WrongEventHandlerDeclaration(\sprintf('An event-handler method\'s "%s::%s()" must not have union types.', $class, $method->getName()));
        }

        $paramTypeName = $paramType->getName();

        if ('object' === $paramTypeName) {
            return;
        }

        if ($paramTypeName === $eventType) {
            return;
        }

        if (true === is_a($eventType, $paramTypeName, true)) {
            return;
        }

        throw new WrongEventHandlerDeclaration(\sprintf('The type of an event handler "%s::%s()" must be the same as set event "%s", "%s" provided.', $class, $method->getName(), $eventType, $paramTypeName));
    }
}
