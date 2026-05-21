import React, { useState, useEffect } from 'react';
import gsap from 'gsap';
import type { Room } from '../types';

interface RoomCardProps {
  room: Room;
  onSelect: (room: Room) => void;
}

/**
 * RoomCard Component
 * Displays individual room with GSAP hover animations
 */
const RoomCard: React.FC<RoomCardProps> = ({ room, onSelect }) => {
  const [isHovered, setIsHovered] = useState(false);
  const imageRef = React.useRef<HTMLImageElement>(null);

  useEffect(() => {
    if (!imageRef.current) return;

    if (isHovered) {
      gsap.to(imageRef.current, {
        scale: 1.05,
        duration: 0.4,
        ease: 'power3.out',
      });
    } else {
      gsap.to(imageRef.current, {
        scale: 1,
        duration: 0.4,
        ease: 'power3.out',
      });
    }
  }, [isHovered]);

  return (
    <div 
      className="card min-w-[80%] md:min-w-[55%] lg:min-w-[40%] cursor-pointer flex-shrink-0"
      onClick={() => onSelect(room)}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      <div className="relative rounded-2xl overflow-hidden bg-white shadow-lg group">
        <img
          ref={imageRef}
          src={`/images/${room.image_url}`}
          alt={room.name}
          className="aspect-square w-full object-cover transition duration-500"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        
        <div className="absolute bottom-0 p-6 text-white w-full">
          <h3 className="text-2xl font-serif mb-2">{room.name}</h3>
          <p className="text-sm text-white/90 mb-4 line-clamp-2">{room.description}</p>
          
          <div className="flex flex-col gap-4">
            <div>
              <p className="text-xs text-white/70 mb-1">Starting from</p>
              <p className="text-xl font-bold text-[#C6A15B]">₱{room.price.toLocaleString()}</p>
            </div>

            <div className="flex items-center justify-between gap-4">
              <button
                className="bg-[#C6A15B] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#B99D75] transition"
                onClick={(e: React.MouseEvent<HTMLButtonElement>) => {
                  e.stopPropagation();
                  onSelect(room);
                }}
              >
                Reserve Now
              </button>
              <a
                href={`pages/room_details.html?id=${room.id}`}
                className="text-xs uppercase tracking-wide text-white/80 hover:text-white transition"
                onClick={(e: React.MouseEvent<HTMLAnchorElement>) => e.stopPropagation()}
              >
                Book Direct
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default RoomCard;

