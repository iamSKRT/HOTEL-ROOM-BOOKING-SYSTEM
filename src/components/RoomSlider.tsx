import React, { useState, useEffect, useRef } from 'react';
import gsap from 'gsap';
import RoomCard from './RoomCard';
import type { Room } from '../types';

interface RoomSliderProps {
  rooms: Room[];
  onRoomSelect: (room: Room) => void;
  isLoading: boolean;
}

/**
 * RoomSlider Component
 * Carousel for rooms with drag, scroll, and dot navigation
 * Preserves existing GSAP animations and design
 */
const RoomSlider: React.FC<RoomSliderProps> = ({ rooms, onRoomSelect, isLoading }) => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isDragging, setIsDragging] = useState(false);
  const trackRef = useRef<HTMLDivElement>(null);
  const dotsContainerRef = useRef<HTMLDivElement>(null);
  const startXRef = useRef(0);
  const currentXRef = useRef(0);

  const cardWidth = 350;
  const gap = 24;
  const itemWidth = cardWidth + gap;

  // Initialize dots
  useEffect(() => {
    if (!dotsContainerRef.current) return;
    
    dotsContainerRef.current.innerHTML = '';
    rooms.forEach((_, index) => {
      const dot = document.createElement('button');
      dot.className = `w-2.5 h-2.5 rounded-full ${
        index === 0 ? 'w-6 bg-white' : 'bg-white/30'
      } transition-all duration-300`;
      dot.onclick = () => goTo(index);
      dotsContainerRef.current?.appendChild(dot);
    });
  }, [rooms.length]);

  // Update dots styling
  useEffect(() => {
    if (!dotsContainerRef.current) return;
    
    const dots = dotsContainerRef.current.querySelectorAll('button');
    dots.forEach((dot, index) => {
      if (index === currentIndex) {
        dot.className = 'w-6 h-2.5 rounded-full bg-white transition-all duration-300';
      } else {
        dot.className = 'w-2.5 h-2.5 rounded-full bg-white/30 transition-all duration-300';
      }
    });
  }, [currentIndex]);

  const goTo = (index: number) => {
    const newIndex = Math.max(0, Math.min(index, rooms.length - 1));
    setCurrentIndex(newIndex);

    if (trackRef.current) {
      gsap.to(trackRef.current, {
        x: -newIndex * itemWidth,
        duration: 1,
        ease: 'power3.out',
      });
    }
  };

  const handleMouseDown = (e: React.MouseEvent<HTMLDivElement>) => {
    setIsDragging(true);
    startXRef.current = e.clientX;
  };

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (!isDragging || !trackRef.current) return;

    currentXRef.current = e.clientX;
    const diff = currentXRef.current - startXRef.current;

    gsap.set(trackRef.current, {
      x: -currentIndex * itemWidth + diff,
    });
  };

  const handleMouseUp = () => {
    if (!isDragging) return;
    setIsDragging(false);

    const diff = currentXRef.current - startXRef.current;

    if (diff < -80) {
      goTo(currentIndex + 1);
    } else if (diff > 80) {
      goTo(currentIndex - 1);
    } else {
      goTo(currentIndex);
    }
  };

  const handleMouseLeave = () => {
    if (isDragging) {
      setIsDragging(false);
      goTo(currentIndex);
    }
  };

  const handleTouchStart = (e: React.TouchEvent<HTMLDivElement>) => {
    startXRef.current = e.touches[0].clientX;
  };

  const handleTouchEnd = (e: React.TouchEvent<HTMLDivElement>) => {
    const diff = e.changedTouches[0].clientX - startXRef.current;

    if (diff < -80) {
      goTo(currentIndex + 1);
    } else if (diff > 80) {
      goTo(currentIndex - 1);
    } else {
      goTo(currentIndex);
    }
  };

  if (isLoading) {
    return (
      <div className="flex justify-center items-center py-20">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#C6A15B]"></div>
      </div>
    );
  }

  if (rooms.length === 0) {
    return (
      <div className="text-center py-20 text-gray-500">
        <p>No rooms available</p>
      </div>
    );
  }

  return (
    <div className="w-full">
      {/* Room Slider Track */}
      <div
        ref={trackRef}
        className="flex gap-6 cursor-grab active:cursor-grabbing"
        onMouseDown={handleMouseDown}
        onMouseMove={handleMouseMove}
        onMouseUp={handleMouseUp}
        onMouseLeave={handleMouseLeave}
        onTouchStart={handleTouchStart}
        onTouchEnd={handleTouchEnd}
      >
        {rooms.map((room) => (
          <RoomCard key={room.id} room={room} onSelect={onRoomSelect} />
        ))}
      </div>

      {/* Dots Navigation */}
      <div
        ref={dotsContainerRef}
        className="flex justify-center mt-8 gap-3"
      />
    </div>
  );
};

export default RoomSlider;
